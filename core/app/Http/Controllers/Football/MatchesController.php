<?php

namespace App\Http\Controllers\Football;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\League;
use App\Models\Round;
use App\Models\Fixture;
use App\Services\ApiClientService;
use App\Services\LiveMatchesService;
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

    public function __construct(ApiClientService $apiClient, LiveMatchesService $handleMatchesService)
    {
        $this->apiClient = $apiClient;
        $this->handleMatchesService = $handleMatchesService;
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

        return view('frontEnd.custom.matches', [
            'locale'    => $locale,
            'matches'   => $fixtures,
            'activeTab' => $selectedDate,
            'dates'     => $dates,
        ]);
    }

    public function indexOld(Request $request)
    {
        $this->website_status();

        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        $start = Carbon::today()->subDays(2)->timezone(Helper::getUserTimezone());
        $end = Carbon::today()->addDays(5)->timezone(Helper::getUserTimezone());

        $dates = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = [
                'key' => $date->toDateString(),
                'label' => $date->isToday()
                    ? 'اليوم'
                    : ($date->isYesterday()
                        ? 'أمس'
                        : ($date->isTomorrow()
                            ? 'غدًا'
                            : $date->translatedFormat('l'))),
                'date' => $date->translatedFormat('M d'),
                'is_today' => $date->isToday(),
            ];
        }


        // $date    = now()->toDateString();
        $date = $request->get('date', now()->timezone(Helper::getUserTimezone())->toDateString());
        $date_live = Carbon::parse($date)->subHours(3)->toDateString();
        $tab = now();
        if ($date !== null) {
            $tab = $date;
        }
        $fixtures = Fixture::whereDate('starting_at', $date)
            ->with(['homeTeam', 'awayTeam', 'league', 'season'])
            ->whereHas('season', function ($q) {
                $q->where('is_current', true);
            })
            ->orderBy('starting_at', 'desc')
            ->paginate(40);

        return view('frontEnd.custom.matches', [
            'locale' => $locale,
            'matches' => $fixtures,
            'activeTab' => $tab,
            'dates' => $dates,
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

    public function showFixture(Request $request, int $id)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $refresh = ((int)$request->query('refresh', 0)) === 1;
        $cacheKey = "sportmonks:fixture_details:{$id}:{$locale}";

        if ($refresh) {
            Cache::forget($cacheKey);
            Cache::forget("sm:fixture_prob:{$id}:{$locale}");
        }

        // ✅ نحدد TTL بناء على آخر حالة مخزنة (لو موجودة)
        $cached = Cache::get($cacheKey);
        $ttlSeconds = (data_get($cached, 'status') === 'LIVE') ? 1 : 1;
        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->find($id);
        // $data = Cache::remember($cacheKey, $ttlSeconds, function () use ($id, $token, $locale) {
        $data = $this->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
        // });

        if (!$data) {
            return view('frontEnd.football.match-details', [
                'fixtureId' => $id,
                'locale' => $locale,
                'fx' => null,
                'fixture' => $fixture,
                'err' => 'تعذر جلب بيانات المباراة من API',
            ]);
        }

        // ✅ بعد الجلب: لو LIVE خزنه 15 ثانية، غير كذا ساعة
        $ttl = (($data['status'] ?? 'NS') === 'LIVE') ? now()->addSeconds(15) : now()->addHour();
        Cache::put($cacheKey, $data, $ttl);

        return view('frontEnd.football.match-details', [
            'fixtureId' => $id,
            'locale' => $locale,
            'fx' => $data,
            'fixture' => $fixture,
            'err' => null,
        ]);
    }

    public function fixtureLiveDetails(Request $request, int $id)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $data = $this->fetchFixtureDetailsFromSportmonks($id, $token, $locale);

        return response()->json([
            'ok' => true,
            'id' => $id,
            'data' => $data,
            'time' => now()->toDateTimeString(),
        ]);
    }

    private function fetchFixtureDetailsFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;participants;scores;events;events.player;events.type;lineups;lineups.player;lineups.position;statistics.type;metadata;odds;periods";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->find($fixtureId);

        $participants = collect(data_get($match, 'participants', []));
        $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
        $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

        $homeId = (int) $this->safeTeamId($home);
        $awayId = (int) $this->safeTeamId($away);

        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = $this->handleMatchesService->extractGoalsFromScores($scoresArr);



        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name');



        $stateCode = $this->handleMatchesService->normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = $this->handleMatchesService->isFinishedState($stateCode, $stateName, $resultInfo);
        $isHalf     = $this->handleMatchesService->isHalfTimeState($stateCode, $stateName);
        $isLive     = $this->handleMatchesService->isLiveState($stateCode, $stateName);

        $status = 'NS';
        if ($isFinished) $status = 'FT';
        elseif ($isHalf) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';
        elseif ($stateCode === "INPLAY_2ND") $status = 'LIVE';
        elseif ($stateCode === "INPLAY_1ST") $status = 'LIVE';

        $minute =
            data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time')
            ?? null;

        if (!is_numeric($minute)) {
            $minute = $this->handleMatchesService->extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        if (!is_numeric($minute)) {
            $minute = $this->handleMatchesService->extractMinuteFromEvents((array) data_get($match, 'events', []));
        }

        $minute = is_numeric($minute) ? (int) $minute : null;

        $eventsRaw = (array) data_get($match, 'events', []);
        $events = $this->handleMatchesService->normalizeEvents($eventsRaw, $homeId, $awayId);

        // $minute = data_get($match, 'time.minute') ?? data_get($match, 'time.current_minute');
        // $minute = is_numeric($minute) ? (int)$minute : null;

        // if ($minute === null) {
        // $minute = collect($eventsRaw)->pluck('minute')->filter()->max();
        // $minute = is_numeric($minute) ? (int)$minute : null;
        // }

        $lineups = collect(data_get($match, 'lineups', []))->values()->all();

        $statsRaw = data_get($match, 'statistics', []);
        $statsRaw = is_array($statsRaw) ? $statsRaw : [];
        if (isset($statsRaw['data']) && is_array($statsRaw['data'])) {
            $statsRaw = $statsRaw['data'];
        }
        $statsRows = $this->normalizeStats($statsRaw, $homeId, $awayId, $home, $away);

        // ✅ probabilities (لو عندك add-on سيطلع، غير كذا null)
        $probabilities = null;

        $odds = data_get($match, 'odds', []);
        $odds = is_array($odds) ? $odds : [];

        $ox = $this->extractOdds1X2FromFixtureOdds($odds);

        if ($ox) {
            $probabilities = $this->oddsToProbabilitiesFromValues(
                (float)($ox['home_value'] ?? 0),
                (float)($ox['draw_value'] ?? 0),
                (float)($ox['away_value'] ?? 0),
            );
        }



        return [
            'id' => (int) data_get($match, 'id'),
            'starting_at' => data_get($match, 'starting_at'),
            'status' => $status,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'minute' =>  $minute,

            'home' => [
                'id' => $homeId,
                'name' => data_get($home, 'name', ''),
                'logo' => data_get($home, 'image_path', ''),
            ],
            'away' => [
                'id' => $awayId,
                'name' => data_get($away, 'name', ''),
                'logo' => data_get($away, 'image_path', ''),
            ],

            'score' => [
                'home' => $homeScore,
                'away' => $awayScore,
            ],

            'events' => $events,
            'fixture' => $fixture ?? null,
            'locale' => $locale ?? 'ar',
            'lineups' => $lineups,
            'statistics_rows' => $statsRows,

            'probabilities' => $probabilities,
            'predictable'   => data_get($match, 'metadata.predictable'),
        ];
    }

    private function normalizeStats(array $stats, int $homeId, int $awayId, $homeParticipant, $awayParticipant): array
    {
        $stats = collect($stats)->filter(fn($x) => is_array($x))->values();

        $homeAlt = [
            (int) data_get($homeParticipant, 'id', 0),
            (int) data_get($homeParticipant, 'participant_id', 0),
        ];
        $awayAlt = [
            (int) data_get($awayParticipant, 'id', 0),
            (int) data_get($awayParticipant, 'participant_id', 0),
        ];

        $pickValue = function ($row) {
            return data_get($row, 'data.value')
                ?? data_get($row, 'value')
                ?? data_get($row, 'data');
        };

        $format = function ($v) {
            if ($v === null || $v === '') return '-';
            if (is_string($v) && str_contains($v, '%')) return $v;
            if (is_numeric($v)) {
                $n = (float)$v;
                if ($n > 0 && $n < 1) return round($n * 100) . '%';
                return (string)((int)$n);
            }
            return (string)$v;
        };

        // ✅ label: type.name -> type_id -> fallback
        $grouped = $stats->groupBy(function ($s) {
            $name = data_get($s, 'type.name')
                ?? data_get($s, 'type.data.name')
                ?? data_get($s, 'type')
                ?? data_get($s, 'name');

            if (is_string($name) && trim($name) !== '') {
                return trim($name);
            }

            $typeId = data_get($s, 'type_id');
            if (is_numeric($typeId)) {
                return 'type_id:' . (int)$typeId; // ✅ ما عاد Unknown
            }

            return 'Unknown';
        });

        $rows = [];

        foreach ($grouped as $label => $items) {
            $label = trim((string)$label);
            if ($label === '') continue;

            $homeRow = $items->first(fn($r) => in_array((int) data_get($r, 'participant_id', 0), $homeAlt, true));
            $awayRow = $items->first(fn($r) => in_array((int) data_get($r, 'participant_id', 0), $awayAlt, true));

            $hv = $homeRow ? $pickValue($homeRow) : null;
            $av = $awayRow ? $pickValue($awayRow) : null;

            // ✅ لو ما لقينا home/away بالمطابقة، خذ أول 2 كـ fallback
            if ($homeRow === null && $awayRow === null) {
                $items = $items->values();
                $hv = $pickValue($items->get(0));
                $av = $pickValue($items->get(1));
            }

            $rows[] = [
                'label' => $label,
                'home'  => $format($hv),
                'away'  => $format($av),
            ];
        }

        // ✅ إذا label type_id:123، نقدر نحوله لاسم عربي لاحقاً (اختياري)
        return collect($rows)->values()->all();
    }

    private function safeTeamId($participant)
    {
        return data_get($participant, 'id') ?: data_get($participant, 'participant_id');
    }


    private function extractWinProbabilitiesFromPredictions(array $predictions): ?array
    {
        // نبحث عن prediction فيه keys: home/draw/away
        // غالباً 3-way result market يكون type_id = 237 (حسب مثال docs)
        $row = collect($predictions)
            ->filter(fn($x) => is_array($x))
            ->first(function ($x) {
                $p = data_get($x, 'predictions', []);
                return is_array($p) && isset($p['home'], $p['draw'], $p['away']);
            });

        if (!$row) return null;

        $p = data_get($row, 'predictions', []);
        $home = (float) ($p['home'] ?? 0);
        $draw = (float) ($p['draw'] ?? 0);
        $away = (float) ($p['away'] ?? 0);

        // sanitize to 100%
        $sum = $home + $draw + $away;
        if ($sum <= 0) return null;

        if (abs($sum - 100) > 0.01) {
            $home = round(($home / $sum) * 100, 0);
            $draw = round(($draw / $sum) * 100, 0);
            $away = max(0, 100 - $home - $draw);
        }

        return ['home' => (int)$home, 'draw' => (int)$draw, 'away' => (int)$away];
    }

    private function fetchWinProbabilitiesFromPredictionsEndpoint(int $fixtureId, string $token, string $locale): ?array
    {
        // حسب الدوكس: predictions base url + probabilities by fixture id
        // https://api.sportmonks.com/v3/football/predictions/probabilities/fixtures/{fixture_id}
        $url = "https://api.sportmonks.com/v3/football/predictions/probabilities/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=type";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $rows = data_get($res, 'json.data', []);
        dd(123, $rows);
        $rows = is_array($rows) ? $rows : [];

        return $this->extractWinProbabilitiesFromPredictions($rows);
    }

    private function fetchOdds1X2ByFixture(int $fixtureId, string $token, string $locale): ?array
    {
        // ✅ Endpoint شائع في SportMonks Odds Feed
        $url = "https://api.sportmonks.com/v3/football/odds/pre-match/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) {
            logger()->warning('Odds endpoint failed', [
                'http' => data_get($res, 'status'),
                'json' => data_get($res, 'json'),
            ]);
            return null;
        }

        $rows = data_get($res, 'json.data', []);
        $rows = is_array($rows) ? $rows : [];

        // ✅ نحاول نلقط 1X2: home/draw/away
        // شكل الداتا يختلف، فنمشي بفلاتر مرنة
        $homeOdd = null;
        $drawOdd = null;
        $awayOdd = null;

        foreach ($rows as $r) {
            $label = strtolower((string)(data_get($r, 'label') ?? data_get($r, 'name') ?? data_get($r, 'market.name') ?? ''));
            $market = strtolower((string)(data_get($r, 'market.description') ?? data_get($r, 'market.name') ?? ''));

            // نحاول نحدد سوق 1X2 / fulltime result
            $is1x2 = str_contains($label, '1x2')
                || str_contains($market, '1x2')
                || str_contains($market, 'fulltime result')
                || str_contains($market, 'match winner');

            if (!$is1x2) continue;

            // كثير من الردود تكون outcomes داخل bookie/values
            $values = data_get($r, 'values') ?? data_get($r, 'outcomes') ?? data_get($r, 'bookmaker.data.0.odds') ?? null;

            if (is_array($values)) {
                foreach ($values as $v) {
                    $out = strtolower((string)(data_get($v, 'label') ?? data_get($v, 'name') ?? data_get($v, 'outcome') ?? ''));
                    $odd = data_get($v, 'value') ?? data_get($v, 'odd') ?? data_get($v, 'probability') ?? null;
                    $odd = is_numeric($odd) ? (float)$odd : null;

                    // home / draw / away
                    if ($odd) {
                        if (in_array($out, ['1', 'home', 'team1'], true)) $homeOdd = $odd;
                        elseif (in_array($out, ['x', 'draw', 'tie'], true)) $drawOdd = $odd;
                        elseif (in_array($out, ['2', 'away', 'team2'], true)) $awayOdd = $odd;
                    }
                }
            }

            if ($homeOdd && $drawOdd && $awayOdd) break;
        }

        if (!$homeOdd || !$drawOdd || !$awayOdd) return null;

        return [
            'home_odd' => $homeOdd,
            'draw_odd' => $drawOdd,
            'away_odd' => $awayOdd,
        ];
    }
    private function oddsToProbabilities(?float $homeOdd, ?float $drawOdd, ?float $awayOdd): ?array
    {
        if (!$homeOdd || !$drawOdd || !$awayOdd) return null;
        if ($homeOdd <= 0 || $drawOdd <= 0 || $awayOdd <= 0) return null;

        $ih = 1 / $homeOdd;
        $id = 1 / $drawOdd;
        $ia = 1 / $awayOdd;
        $sum = $ih + $id + $ia;
        if ($sum <= 0) return null;

        $home = round(($ih / $sum) * 100, 0);
        $draw = round(($id / $sum) * 100, 0);
        $away = max(0, 100 - $home - $draw);

        return ['home' => (int)$home, 'draw' => (int)$draw, 'away' => (int)$away];
    }

    private function extractOdds1X2FromFixtureOdds(array $odds): ?array
    {
        $odds = collect($odds)->filter(fn($o) => is_array($o))->values();

        // normalize outcome label to one of: home/draw/away
        $norm = function ($o): ?string {
            $label = strtoupper(trim((string)(
                data_get($o, 'label')
                ?? data_get($o, 'name')
                ?? data_get($o, 'outcome')
                ?? data_get($o, 'selection')
                ?? data_get($o, 'type')
                ?? ''
            )));

            // common variants
            if (in_array($label, ['1', 'HOME', 'TEAM1', 'H'], true)) return 'home';
            if (in_array($label, ['X', 'DRAW', 'TIE', 'D'], true)) return 'draw';
            if (in_array($label, ['2', 'AWAY', 'TEAM2', 'A'], true)) return 'away';

            return null;
        };

        // extract numeric odd value
        $oddValue = function ($o): ?float {
            $v = data_get($o, 'value')
                ?? data_get($o, 'odd')
                ?? data_get($o, 'odds')
                ?? data_get($o, 'rate')
                ?? null;

            return is_numeric($v) ? (float)$v : null;
        };

        // keep only odds that look like 1/X/2 outcomes
        $candidates = $odds
            ->map(function ($o) use ($norm, $oddValue) {
                $k = $norm($o);
                $v = $oddValue($o);
                if (!$k || !$v) return null;

                return [
                    '_k' => $k,               // home/draw/away
                    '_v' => $v,               // numeric odd
                    '_market_id' => (int)(data_get($o, 'market_id') ?? data_get($o, 'market.id') ?? 0),
                    '_bookmaker_id' => (int)(data_get($o, 'bookmaker_id') ?? data_get($o, 'bookmaker.id') ?? 0),
                    '_ts' => (int)(data_get($o, 'latest_bookmaker_update') ?? data_get($o, 'updated_at_timestamp') ?? 0),
                    '_raw' => $o,
                ];
            })
            ->filter()
            ->values();

        if ($candidates->isEmpty()) return null;

        // group by market+bookmaker, pick any group that has home/draw/away
        $groups = $candidates->groupBy(function ($x) {
            return $x['_market_id'] . ':' . $x['_bookmaker_id'];
        });

        $best = null;
        $bestTs = -1;

        foreach ($groups as $key => $items) {
            $keys = $items->pluck('_k')->unique()->values()->all();
            if (!in_array('home', $keys, true) || !in_array('draw', $keys, true) || !in_array('away', $keys, true)) {
                continue;
            }

            // pick group with newest timestamp
            $ts = (int) $items->max('_ts');
            if ($ts > $bestTs) {
                $bestTs = $ts;
                $best = $items;
            }
        }

        if (!$best) return null;

        $home = $best->firstWhere('_k', 'home');
        $draw = $best->firstWhere('_k', 'draw');
        $away = $best->firstWhere('_k', 'away');

        if (!$home || !$draw || !$away) return null;

        return [
            'home_value' => (float) $home['_v'],
            'draw_value' => (float) $draw['_v'],
            'away_value' => (float) $away['_v'],
            'meta' => [
                'market_id' => (int) $home['_market_id'],
                'bookmaker_id' => (int) $home['_bookmaker_id'],
                'timestamp' => (int) $bestTs,
            ],
        ];
    }

    private function oddsToProbabilitiesFromValues(float $homeOdd, float $drawOdd, float $awayOdd): ?array
    {
        if ($homeOdd <= 0 || $drawOdd <= 0 || $awayOdd <= 0) return null;

        $ih = 1 / $homeOdd;
        $id = 1 / $drawOdd;
        $ia = 1 / $awayOdd;
        $sum = $ih + $id + $ia;
        if ($sum <= 0) return null;

        $home = (int) round(($ih / $sum) * 100, 0);
        $draw = (int) round(($id / $sum) * 100, 0);
        $away = max(0, 100 - $home - $draw);

        return ['home' => $home, 'draw' => $draw, 'away' => (int)$away];
    }

    private function parsePercent(string $s): ?int
    {
        $s = trim($s);
        if ($s === '') return null;
        $s = str_replace('%', '', $s);
        return is_numeric($s) ? (int) round((float)$s, 0) : null;
    }
}
