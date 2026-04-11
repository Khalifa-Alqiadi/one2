<?php

namespace App\Http\Controllers\Football;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\League;
use App\Models\Round;
use App\Models\Fixture;
use App\Services\ApiClientService;
use App\Services\FetchFixtureDetailsFromSportmonksService;
use App\Services\LiveMatchesService;
use App\Services\SportmonksStandingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class MatchesController extends Controller
{
    // =========================
    //  A) صفحة العرض من DB
    // =========================

    private ApiClientService $apiClient;
    private LiveMatchesService $handleMatchesService;
    private FetchFixtureDetailsFromSportmonksService $fetchFixtureDetailsFromSportmonks;

    public function __construct(
        ApiClientService $apiClient,
        LiveMatchesService $handleMatchesService,
        FetchFixtureDetailsFromSportmonksService $fetchFixtureDetailsFromSportmonks
        )
    {
        $this->apiClient = $apiClient;
        $this->handleMatchesService = $handleMatchesService;
        $this->fetchFixtureDetailsFromSportmonks = $fetchFixtureDetailsFromSportmonks;
    }

    public function index(Request $request)
    {
        $this->website_status();

        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale    = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';
        $userTz    = Helper::getUserTimezone() ?: 'UTC';

        // اليوم المحلي للمستخدم
        $todayLocal = now($userTz)->startOfDay();

        // tabs: يومين قبل + 5 بعد
        $startLocal = $todayLocal->copy()->subDays(2);
        $endLocal   = $todayLocal->copy()->addDays(5);

        $dates = [];
        for ($date = $startLocal->copy(); $date->lte($endLocal); $date->addDay()) {
            $dates[] = [
                'key'      => $date->toDateString(),
                'label'    => $date->isToday()
                    ? __('frontend.today')
                    : ($date->isYesterday()
                        ? __('frontend.yesterday')
                        : ($date->isTomorrow()
                            ? __('frontend.tomorrow')
                            : $date->translatedFormat('l'))),
                'date'     => $date->translatedFormat('M d'),
                'is_today' => $date->isSameDay($todayLocal),
            ];
        }

        // التاريخ المختار من التاب - بصيغة تاريخ محلي للمستخدم
        $selectedDate = $request->get('date', $todayLocal->toDateString());

        // بداية ونهاية هذا اليوم بتوقيت المستخدم
        $selectedStartLocal = Carbon::parse($selectedDate, $userTz)->startOfDay();
        $selectedEndLocal   = Carbon::parse($selectedDate, $userTz)->endOfDay();

        // نحولها إلى UTC للاستعلام من قاعدة البيانات
        $selectedStartUtc = $selectedStartLocal->copy()->utc();
        $selectedEndUtc   = $selectedEndLocal->copy()->utc();

        $fixtures = Fixture::query()
            ->whereBetween('starting_at', [$selectedStartUtc, $selectedEndUtc])
            ->with(['homeTeam', 'awayTeam', 'league', 'season'])
            ->whereHas('season', function ($q) {
                $q->where('is_current', true);
            })
            ->orderBy('starting_at')
            ->paginate(40)
            ->appends($request->query());

        return view('frontEnd.football.matches', [
            'locale'    => $locale,
            'matches'   => $fixtures,
            'activeTab' => $selectedDate,
            'dates'     => $dates,
        ]);
    }

    // =========================
    // A) عرض صفحة الدوري (من DB)
    // =========================
    public function show(Request $request, $id = null)
    {
        $leagueId = (int) $id;
        $league = League::findOrFail($leagueId);

        $locale = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';

        // season
        $season = $league->seasons()->where('is_current', 1)->first();
        $seasonId = (int) $request->query('season_id', 0);
        if ($seasonId <= 0) {
            $seasonId = $season->id;
        } else {
            $season = $league->seasons()->where('id', $seasonId)->first();
        }

        // ✅ standings من API مع كاش ساعة
        if ($request->boolean('refresh_standings')) {
            $this->refreshStandingsCache($seasonId, $locale);
        }

        $standingsPack = $this->getStandingsCached($seasonId, $locale);
        $standings = $standingsPack['standings'] ?? [];
        $standingsErr = $standingsPack['ok'] ? null : ($standingsPack['error'] ?? 'Standings error');
        // وقت آخر تحديث (من الكاش)
        $standingsUpdatedAt = $standingsPack['fetched_at'] ?? null;

        $perPage = 1;

        $baseQuery = Round::query()
            ->where('league_id', $leagueId)
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->orderBy('starting_at', 'asc');

        $roundsCount = $baseQuery->count();

        $totalRounds = 0;
        $rounds = [];
        // (اختياري) standings
        $standingsErr = null;

        $fixturesErr = null;
        $roundsErr = null;

        $stages = $league->stages()
            ->with([
                'rounds' => function ($q) use ($seasonId) {
                    $q->where('season_id', $seasonId)
                        ->orderBy('starting_at', 'asc');
                },
                'fixtures' => function ($fx) {
                    $fx->orderBy('starting_at', 'asc');
                },
                'fixtures.homeTeam',
                'fixtures.awayTeam',
            ])
            ->where('season_id', $seasonId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('starting_at', 'asc')
            ->get();

        $pages = collect();
        $name_var = 'name_' . @Helper::currentLanguage()->code;
        foreach ($stages as $stage) {
            $stageName = mb_strtolower((string) ($stage->name ?? ''));
            if (count($stage->rounds) > 0) {

                // اعتبر هذه المرحلة الأساسية لو عندها عدة جولات
                $isLeaguePhase = $stage->rounds->count() > 1;

                if ($isLeaguePhase) {
                    $roundsCount = $stage->rounds->count();
                    foreach ($stage->rounds as $round) {
                        $pages->push([
                            'type' => 'round',
                            'title' => __('frontend.matchday_progress', [
                                'current' => $round->name,
                                'total' => $roundsCount,
                            ]),
                            'stage' => $stage,
                            'round' => $round,
                            'fixtures' => $round->fixtures,
                        ]);
                    }
                } else {
                    // المراحل الإقصائية كصفحة واحدة
                    $fixtures = $stage->rounds
                        ->flatMap(fn($round) => $round->fixtures)
                        ->sortBy('starting_at')
                        ->values();

                    $pages->push([
                        'type' => 'stage',
                        'title' => $stage->$name_var ?: ('Stage ' . $stage->id),
                        'stage' => $stage,
                        'round' => null,
                        'fixtures' => $fixtures,
                    ]);
                }
            } else {
                $fixtures = Fixture::where('stage_id', $stage->id)
                    ->orderBy('starting_at', 'desc')
                    ->get();
                $pages->push([
                    'type' => 'stage',
                    'title' => $stage->$name_var ?: ('Stage ' . $stage->id),
                    'stage' => $stage,
                    'round' => null,
                    'fixtures' => $fixtures,
                ]);
            }
        }

        $perPage = 1;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $pages->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedPages = new LengthAwarePaginator(
            $currentItems,
            $pages->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        if (!$request->has('page')) {
            $targetIndex = 0;

            foreach ($pages as $index => $page) {
                if ($page['type'] === 'round' && $page['round'] && $page['round']->is_current) {
                    $targetIndex = $index;
                    break;
                }

                if ($page['type'] === 'stage' && $page['stage'] && $page['stage']->is_current) {
                    $targetIndex = $index;
                    break;
                }
            }

            $targetPage = $targetIndex + 1;

            return redirect()->route('league.rounds', [
                'id' => $id,
                'season_id' => $seasonId,
                'page' => $targetPage,
            ]);
        }

        $seasons = $league->seasons()->orderByDesc('starting_at')->get();

        return view('frontEnd.custom.league-matches.index', compact(
            'league',
            'leagueId',
            'seasonId',
            'seasons',
            'season',
            'rounds',
            'locale',
            'totalRounds',
            'fixturesErr',
            'roundsErr',
            'roundsCount',
            'standings',
            'standingsErr',
            'standingsUpdatedAt',
            'stages',
            'paginatedPages'
        ));
    }


    private function getStandingsCached(int $seasonId, string $locale): array
    {
        $token = config('services.SPORTMONKS_TOKEN');

        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";

        // كاش ساعة
        $data = Cache::remember($cacheKey, 3600, function () use ($seasonId, $token, $locale, $metaKey) {

            $url = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale={$locale}"
                // حاول تجيب participant + details + form (لو include غير مدعوم عندك احذفه)
                . "&include=participant;details.type;form";

            $res = $this->apiClient->curlGet($url);


            if (!data_get($res, 'ok')) {
                // رجع فاضي وخلي الخطأ يتحكم فيه show()
                return [
                    'ok' => false,
                    'error' => data_get($res, 'error', 'Standings API failed'),
                    'data' => [],
                ];
            }

            $json = data_get($res, 'json', []);
            $rows = data_get($json, 'data', []);

            // خزّن وقت آخر تحديث
            Cache::put($metaKey, ['fetched_at' => now()->toDateTimeString()], 3600);

            return [
                'ok' => true,
                'error' => null,
                'data' => is_array($rows) ? $rows : [],
            ];
        });

        // meta (وقت التحديث)
        $meta = Cache::get($metaKey, []);
        $fetchedAt = data_get($meta, 'fetched_at');

        return [
            'ok' => (bool) data_get($data, 'ok', false),
            'error' => data_get($data, 'error'),
            'standings' => data_get($data, 'data', []),
            'fetched_at' => $fetchedAt,
        ];
    }

    // اختياري: تحديث يدوي (إذا حط refresh_standings=1)
    private function refreshStandingsCache(int $seasonId, string $locale): void
    {
        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";
        Cache::forget($cacheKey);
        Cache::forget($metaKey);
    }

    private function buildFixtureDetailsFromDatabase(Fixture $fixture, string $locale): array
    {
        $name_var = 'name_' . $locale;
        return [
            'id' => (int) $fixture->id,
            'starting_at' => $fixture->starting_at,
            'status' => $fixture->state ?? 'NS',
            'state_code' => $fixture->state_code ?? 'NS',
            'state_name' => $fixture->state_name ?? '',
            'minute' => $fixture->minute,



            'home' => [
                'id' => (int) ($fixture->homeTeam->id ?? 0),
                'name' => data_get($fixture->homeTeam, $name_var, ''),
                'logo' => data_get($fixture->homeTeam, 'image_path', ''),
            ],
            'away' => [
                'id' => (int) ($fixture->awayTeam->id ?? 0),
                'name' => data_get($fixture->awayTeam, $name_var, ''),
                'logo' => data_get($fixture->awayTeam, 'image_path', ''),
            ],

            'score' => [
                'home' => $fixture->home_score,
                'away' => $fixture->away_score,
            ],

            'events' => $fixture->events_json ?? [],
            'fixture' => $fixture,
            'locale' => $locale,
            'lineups' => $fixture->lineups_json ?? [],
            'statistics_rows' => $fixture->statistics_json ?? [],
            'venue' => $fixture->venue_json ?? [],
            'probabilities' => $fixture->win_probabilities_json ?? null,
            'tv_stations' => $fixture->tv_stations_json ?? [],
            'injuries' => $fixture->injuries_json ?? [],
            'suspensions' => $fixture->suspensions_json ?? [],
            'predictable' => null,
        ];
    }

    private function shouldFetchLiveDetails(?string $status, ?string $stateCode): bool
    {
        $status = strtoupper((string) $status);
        $stateCode = strtoupper((string) $stateCode);

        return in_array($status, ['LIVE', 'HT', 'INPLAY_2ND', 'INPLAY_1ST'], true)
            || in_array($stateCode, ['LIVE', 'HT', 'INPLAY_1ST', 'INPLAY_2ND'], true);
    }

    public function showFixture(Request $request, int $id)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->findOrFail($id);

        if ($request->boolean('refresh_standings')) {
            app(SportmonksStandingService::class)->refreshStandingsCache($fixture->season_id, $locale);
        }

        $standingsPack = null;
        $standingsErr = null;

        $standingsData = app(SportmonksStandingService::class)->getStandingsCached($fixture->season_id, $locale);

        $standings = $standingsData['standings'] ?? [];
        $standingsUpdatedAt = $standingsData['fetched_at'] ?? null;

        $isFinished = (bool) $fixture->is_finished;
        $isTimeLive = false;
        if (!$isFinished && $fixture->starting_at) {
            try {
                $start = \Carbon\Carbon::parse($fixture->starting_at);
                $isTimeLive = now()
                    ->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
            } catch (\Throwable $e) {
            }
        }
        $dbStatus = strtoupper((string) ($fixture->state_code ?? 'NS'));
        $dbStateCode = strtoupper((string) ($fixture->state_name ?? $dbStatus));

        $isLive = $this->shouldFetchLiveDetails($dbStatus, $dbStateCode);
        if ($isTimeLive) {
            $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
            // dd($data);

            if ($data) {
                $this->fetchFixtureDetailsFromSportmonks->persistFixtureDetails($fixture, $data);

            } else {
                $data = $this->buildFixtureDetailsFromDatabase($fixture->fresh(['league', 'homeTeam', 'awayTeam']), $locale);
            }
        } else {

            if(!$fixture->lineups_json && $isFinished == true){
                $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
                if ($data) {
                    $this->fetchFixtureDetailsFromSportmonks->persistFixtureDetails($fixture, $data);
                }
            }elseif(!$fixture->lineups_json){
                $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
                if ($data) {
                    $this->fetchFixtureDetailsFromSportmonks->persistFixtureDetails($fixture, $data);
                }
            }
            // $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
            // dd($data);
            $data = $this->buildFixtureDetailsFromDatabase($fixture, $locale);

        }
        // $service = app(\App\Services\FetchCommentaryService::class)->getLiveCommentary($id, $locale);
        // dd($service);
        $name_var = 'name_' . $locale;
        $PageTitle = $fixture->homeTeam->$name_var . ' vs ' . $fixture->awayTeam->$name_var . ' - ' . ($fixture->league->$name_var ?? '');

        return view('frontEnd.football.match-details', [
            'fixtureId' => $id,
            'locale'    => $locale,
            'fx'        => $data,
            'fixture'   => $fixture->fresh(['league', 'homeTeam', 'awayTeam']),
            'err'       => null,
            'PageTitle' => $PageTitle,
            'standings' => $standings,
            'standingsErr' => $standingsErr,
            'standingsUpdatedAt' => $standingsUpdatedAt,
        ]);
    }

    public function liveFixtureDetails(int $id)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->findOrFail($id);

        $dbStatus = strtoupper((string) ($fixture->state ?? 'NS'));
        $dbStateCode = strtoupper((string) ($fixture->state ?? $dbStatus));
        $isFinished = (bool) $fixture->is_finished;
        $isTimeLive = false;
        if (!$isFinished && $fixture->starting_at) {
            try {
                $start = \Carbon\Carbon::parse($fixture->starting_at);
                $isTimeLive = now()
                    ->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
            } catch (\Throwable $e) {
            }
        }

        if ($isTimeLive) {
            $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);

            if ($data) {
                $this->fetchFixtureDetailsFromSportmonks->persistFixtureDetails($fixture, $data);
            } else {
                $data = $this->buildFixtureDetailsFromDatabase($fixture->fresh(['league', 'homeTeam', 'awayTeam']), $locale);
            }
        } else {
            $data = $this->buildFixtureDetailsFromDatabase($fixture, $locale);
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    public function fixtureLiveDetails(Request $request, int $id)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $data = $this->fetchFixtureDetailsFromSportmonks->fetchFixtureDetailsFromSportmonks($id, $token, $locale);

        return response()->json([
            'ok' => true,
            'id' => $id,
            'data' => $data,
            'time' => now()->toDateTimeString(),
        ]);
    }

    public function commentary($id){
        $service = app(\App\Services\FetchCommentaryService::class);
        $data = $service->getLiveCommentary($id, 'ar');
        return response()->json([
            'ok' => $data['ok'],
            'data'=> $data['data']
        ]);
    }
}
