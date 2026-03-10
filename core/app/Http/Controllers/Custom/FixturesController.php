<?php

namespace App\Http\Controllers\Custom;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FixturesController extends Controller
{

    public function index(Request $request)
    {
        $this->website_status();

        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';



        $date    = now()->toDateString();
        $tab = $request->get('tab', 'today');
        if (!in_array($tab, ['yesterday', 'today', 'tomorrow'])) {
            $tab = 'today';
            $date    = now()->toDateString();
        }
        if ($tab === 'yesterday') {
            $date = now()->subDay()->toDateString();
        } elseif ($tab === 'tomorrow') {
            $date = now()->addDay()->toDateString();
        }
        $fixtures = Fixture::whereDate('starting_at', $date)
            ->with(['homeTeam', 'awayTeam', 'league', 'season'])
            ->whereHas('season', function ($q) {
                $q->where('is_current', true);
            })
            ->orderBy('starting_at')
            ->paginate(40);

        return view('frontEnd.custom.matches-today', [
            'locale' => $locale,
            'fixtures' => $fixtures,
            'activeTab' => $tab,
        ]);
    }
    public function indexOld3(Request $request)
    {
        $this->website_status();

        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        // أي تبويب مفتوح
        $tab = $request->get('tab', 'today');


        // Pagination: كل تبويب له page مستقل
        $pageToday    = (int) $request->get('p_today', 1);
        $pageYest     = (int) $request->get('p_yest', 1);
        $pageTom      = (int) $request->get('p_tom', 1);

        $perPage = 100;

        // Includes (بدون time)
        // لو خطتك ما تدعم events/periods بيشتغل عادي بس بدون تفاصيل دقيقة/HT
        $includes = "participants;league;state;scores;round;periods;events";

        $today    = now()->toDateString();
        $yest     = now()->subDay()->toDateString();
        $tom      = now()->addDay()->toDateString();

        $todayErr = $yestErr = $tomErr = null;

        // ✅ كل تبويب نجيب صفحة واحدة فقط (خفيف)
        $todayRes = $this->fetchFixturesByDatePaged($today, $token, $locale, $includes, $todayErr, $perPage, $pageToday);
        $yestRes  = $this->fetchFixturesByDatePaged($yest,  $token, $locale, $includes, $yestErr,  $perPage, $pageYest);
        $tomRes   = $this->fetchFixturesByDatePaged($tom,   $token, $locale, $includes, $tomErr,   $perPage, $pageTom);

        // Sort
        $todays_matches = collect($todayRes['items'])->sortBy('starting_at')->values();
        $yesterdays_matches = collect($yestRes['items'])->sortBy('starting_at')->values();
        $tomorrows_matches = collect($tomRes['items'])->sortBy('starting_at')->values();

        return view('frontEnd.custom.fixtures', [
            'locale' => $locale,
            'activeTab' => $tab,

            // data
            'todays_matches' => $todays_matches,
            'yesterdays_matches' => $yesterdays_matches,
            'tomorrows_matches' => $tomorrows_matches,

            // errors
            'todayErr' => $todayErr,
            'yestErr'  => $yestErr,
            'tomErr'   => $tomErr,

            // pagination meta
            'p_today' => $todayRes['pagination'],
            'p_yest'  => $yestRes['pagination'],
            'p_tom'   => $tomRes['pagination'],

            // current pages for links
            'pageToday' => $pageToday,
            'pageYest'  => $pageYest,
            'pageTom'   => $pageTom,

            'perPage' => $perPage,
        ]);
    }

    /**
     * Returns JSON for today's fixtures used by client-side polling.
     */
    public function todayJson(Request $request)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        $leagueId = (int) $request->query('league_id', 0); // optional filter

        $includes = "participants;league;state;scores;periods;events";
        $today = now()->toDateString();

        $err = null;
        $raw = $this->fetchAllFixturesByDate($today, $token, $locale, $includes, $err, 200, 30);

        $items = collect($raw)
            ->map(fn($fx) => Helper::enrichFixture((array)$fx))
            ->when($leagueId > 0, fn($q) => $q->filter(fn($fx) => (int) data_get($fx, 'league_id', 0) === $leagueId))
            ->map(function ($fx) {
                // Return slim payload for polling
                $parts = collect(data_get($fx, 'participants.data', data_get($fx, 'participants', [])));
                $home = $parts->firstWhere('meta.location', 'home') ?: $parts->get(0);
                $away = $parts->firstWhere('meta.location', 'away') ?: $parts->get(1);

                [$homeScore, $awayScore] = $this->extractScoreForUi($fx, $home, $away);

                return [
                    'id' => data_get($fx, 'id'),
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'computed_minute' => data_get($fx, 'computed_minute'),
                    'computed_is_live' => (bool) data_get($fx, 'computed_is_live'),
                    'state' => [
                        'code' => strtolower((string) data_get($fx, 'state.code', '')),
                        'name' => (string) data_get($fx, 'state.name', ''),
                    ],
                    // display labels (optional)
                    'display_top' => null,
                    'display_sub' => null,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'ok' => true,
            'err' => $err,
            'fixtures' => $items,
        ]);
    }


    /**
     * ✅ جلب مباريات يوم محدد بصفحة واحدة فقط (20) + يرجع pagination meta
     */
    private function fetchFixturesByDatePaged(
        string $date,
        string $token,
        string $locale,
        string $includes,
        ?string &$err = null,
        int $perPage = 20,
        int $page = 1
    ): array {
        $url = "https://api.sportmonks.com/v3/football/fixtures/date/{$date}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=" . urlencode($includes)
            . "&per_page={$perPage}"
            . "&page={$page}";

        $res = $this->curlGet($url);

        if (!$res['ok']) {
            $err = $res['error'] ?? 'Fixtures request failed';
            return [
                'items' => [],
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => 1,
                    'has_more' => false,
                    'total' => 0,
                    'per_page' => $perPage,
                ],
            ];
        }

        $items = data_get($res, 'json.data', []) ?? [];

        // ✅ pagination ممكن تكون في أكثر من مكان حسب الرد
        $pagination = data_get($res, 'json.pagination', []);
        if (empty($pagination)) {
            $pagination = data_get($res, 'json.meta.pagination', []);
        }

        $currentPage = (int) (data_get($pagination, 'current_page')
            ?? data_get($pagination, 'currentPage')
            ?? $page);

        $lastPage = (int) (data_get($pagination, 'last_page')
            ?? data_get($pagination, 'lastPage')
            ?? 1);

        $total = (int) (data_get($pagination, 'total')
            ?? data_get($pagination, 'total_items')
            ?? data_get($pagination, 'totalItems')
            ?? 0);

        $per = (int) (data_get($pagination, 'per_page')
            ?? data_get($pagination, 'perPage')
            ?? $perPage);

        // ✅ لو last_page مش موجود لكن total موجود نحسبه
        if ($lastPage <= 1 && $total > 0 && $per > 0) {
            $lastPage = (int) ceil($total / $per);
        }

        // ✅ لو API يعطي has_more فقط
        $hasMore = (bool) (data_get($pagination, 'has_more')
            ?? data_get($pagination, 'hasMore')
            ?? ($currentPage < $lastPage));

        // ✅ fallback: لو عدد العناصر == perPage غالباً فيه صفحات إضافية
        if ($lastPage <= 1 && count($items) >= $perPage) {
            $hasMore = true;
            $lastPage = $currentPage + 1; // تقدير مؤقت يسمح بإظهار pagination
        }

        return [
            'items' => $items,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page'    => $lastPage,
                'has_more'     => $hasMore,
                'total'        => $total,
                'per_page'     => $per,
            ],
        ];
    }


    private function fetchAllFixturesByDate(string $date, string $token, string $locale, string $includes, ?string &$err = null, int $perPage = 200, int $maxPages = 30): array
    {
        $all = collect();
        $page = 1;
        $hasMore = true;

        while ($hasMore && $page <= $maxPages) {
            $res = $this->fetchFixturesByDatePaged($date, $token, $locale, $includes, $err, $perPage, $page);

            $all = $all->merge($res['items'] ?? []);
            $hasMore = (bool) data_get($res, 'pagination.has_more', false);

            $page++;
            if (empty($res['items'])) break;
        }

        return $all->values()->all();
    }





    public function indexOld()
    {
        $this->website_status();
        $lang = request('lang', 'en'); // ar | en
        $locale = Helper::currentLanguage()->code; // إذا SportMonks يدعمها عندك

        $token = config('services.SPORTMONKS_TOKEN');
        $includes = "participants;league;state;scores;round;periods";
        $today = now()->format('Y-m-d');

        $fixtures_todays_matches = $this->fetchAllBetween($today, $token, $locale, $includes);

        $todays_matches = collect($fixtures_todays_matches)
            ->sortBy('starting_at')
            ->values();


        return view('frontEnd.custom.fixtures', [
            'todays_matches' => $todays_matches,
            'count' => count($todays_matches),
            'locale' => $locale
        ]);
    }

    /**
     * ✅ يجمع كل الصفحات من between
     */
    private function fetchAllBetweenOld(
        string $date,
        string $token,
        string $locale,
        string $includes,
        ?string &$err = null,
        int $perPage = 200,
        int $maxPages = 30
    ): array {
        $all = collect();
        $page = 1;
        $hasMore = true;

        while ($hasMore && $page <= $maxPages) {
            $url = "https://api.sportmonks.com/v3/football/fixtures/date/{$date}"
                . "?api_token={$token}&locale={$locale}"
                . "&include={$includes}"
                . "&per_page={$perPage}"
                . "&page={$page}";

            $res = $this->curlGet($url);

            if (!$res['ok']) {
                $err = $res['error'] ?? 'Fixtures request failed';
                break;
            }

            $data = collect(data_get($res, 'json.data', []));
            if ($data->isEmpty()) break;

            $all = $all->merge($data);

            $hasMore = (bool) data_get($res, 'json.pagination.has_more', false);

            $currentPage = (int) data_get($res, 'json.pagination.current_page', $page);
            $lastPage    = (int) data_get($res, 'json.pagination.last_page', $currentPage);

            if (data_get($res, 'json.pagination.last_page') !== null) {
                $hasMore = $currentPage < $lastPage;
            }

            if (data_get($res, 'json.pagination') === null) {
                $hasMore = false;
            }

            $page++;
        }

        return $all->all();
    }

    public function show(Request $request, int $leagueId)
    {
        $this->website_status();

        $token     = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale    = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        // من المستخدم (اختياري)
        $seasonId = (int) $request->get('season_id', 0);
        $roundId  = (int) $request->get('round_id', 0);

        /** -----------------------------
         *  0) Defaults (عشان ما ينهار الـ Blade)
         * ------------------------------ */
        $fixturesErr = null;
        $roundsErr   = null;

        $standings = [];
        $standingsErr = null;

        $playersBlocks = [];
        $playersErr = null;

        $seasonStats = [];
        $seasonStatsErr = null;

        $topscorers = [];
        $topscorersErr = null;

        /** -----------------------------
         *  1) League info + seasons
         * ------------------------------ */
        $leagueUrl = "https://api.sportmonks.com/v3/football/leagues/{$leagueId}"
            . "?api_token={$token}&locale={$locale}&include=seasons";

        $leagueRes = $this->curlGet($leagueUrl);
        $league    = $leagueRes['ok'] ? data_get($leagueRes, 'json.data') : null;

        // fallback season
        if (!$seasonId && $league) {
            $seasonId = $this->pickCurrentSeasonId($league, 0);
        }

        /** -----------------------------
         *  2) ✅ Full season fixtures (الأفضل)
         *  - schedules/seasons/{seasonId}
         *  - fallback: between + pagination
         * ------------------------------ */
        $fixturesAll = [];

        if ($seasonId > 0) {
            $scheduleUrl = "https://api.sportmonks.com/v3/football/schedules/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}";

            $scheduleRes = $this->curlGet($scheduleUrl);

            if ($scheduleRes['ok']) {
                $fixturesAll = $this->extractFixturesFromSchedule(data_get($scheduleRes, 'json.data', []));
            } else {
                $fixturesErr = $scheduleRes['error'] ?? null;
            }
        }

        // fallback between لو schedule ما نفع
        if (empty($fixturesAll)) {
            $start = now()->subDays(30)->format('Y-m-d');
            $end   = now()->addDays(60)->format('Y-m-d');

            $includes = "participants;league;state;scores;round;periods";

            $fixturesAll = $this->fetchAllBetween($start, $end, $token, $locale, $includes, $fixturesErr);
        }

        // فلترة مباريات هذا الدوري فقط + ترتيب
        $fixturesLeague = collect($fixturesAll)
            ->filter(fn($fx) => (int) data_get($fx, 'league_id', 0) === (int) $leagueId)
            ->sortBy('starting_at')
            ->values();

        /** -----------------------------
         *  3) ✅ تصحيح seasonId من fixtures (إذا المستخدم ما حدده)
         * ------------------------------ */
        $seasonIdFromFixtures = (int) data_get($fixturesLeague, '0.season_id', 0);
        if ($seasonIdFromFixtures > 0 && $request->get('season_id') === null) {
            $seasonId = $seasonIdFromFixtures;
        }

        /** -----------------------------
         *  4) ✅ Rounds (Matchdays)
         *  - الأفضل: rounds/seasons/{seasonId}
         *  - fallback: استخراج unique round_id من fixtures
         * ------------------------------ */
        $rounds = [];

        if ($seasonId > 0) {
            $roundsUrl = "https://api.sportmonks.com/v3/football/rounds/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}&per_page=200";

            $roundsRes = $this->curlGet($roundsUrl);

            if ($roundsRes['ok']) {
                $rounds = data_get($roundsRes, 'json.data', []) ?? [];
            } else {
                $roundsErr = $roundsRes['error'] ?? null;
            }
        }

        // fallback لو endpoint غير متاح
        if (empty($rounds)) {
            $rounds = $fixturesLeague
                ->map(function ($fx) {
                    $rid = (int) (data_get($fx, 'round_id') ?? data_get($fx, 'round.id') ?? 0);
                    return [
                        'id'          => $rid,
                        'name'        => data_get($fx, 'round.name', ''),
                        'starting_at' => data_get($fx, 'starting_at'),
                    ];
                })
                ->filter(fn($r) => !empty($r['id']))
                ->unique('id')
                ->values()
                ->all();
        }

        $roundsCol = collect($rounds)
            ->filter(fn($r) => data_get($r, 'id'))
            ->sortBy(fn($r) => data_get($r, 'starting_at') ?: data_get($r, 'id'))
            ->values();

        $totalRounds = $roundsCol->count();

        /** -----------------------------
         *  4.5) ✅ Default roundId (SMART)
         *  LIVE > Today > Finished > Scheduled
         * ------------------------------ */
        if ($roundId <= 0) {
            $roundId = $this->pickSmartDefaultRoundId($fixturesLeague);
        }

        // لو ما لقى شيء (rare) خذ آخر جولة
        if ($roundId <= 0 && $totalRounds) {
            $roundId = (int) data_get($roundsCol->last(), 'id', 0);
        }

        /** -----------------------------
         *  5) current index + nav ids
         * ------------------------------ */
        $currentIndex = $roundsCol->search(fn($r) => (int) data_get($r, 'id') === (int) $roundId);
        if ($currentIndex === false) {
            // fallback: أقرب جولة موجودة
            $currentIndex = max(0, $totalRounds - 1);
            $roundId = (int) data_get($roundsCol->get($currentIndex), 'id', 0);
        }

        $matchdayNumber = $totalRounds ? ($currentIndex + 1) : 0;

        $prevRoundId = $currentIndex > 0
            ? (int) data_get($roundsCol->get($currentIndex - 1), 'id')
            : null;

        $nextRoundId = ($currentIndex < $totalRounds - 1)
            ? (int) data_get($roundsCol->get($currentIndex + 1), 'id')
            : null;

        $round = $roundsCol->get($currentIndex) ?: null;

        /** -----------------------------
         *  6) ✅ Fixtures للجولة المحددة فقط
         * ------------------------------ */
        $fixtures = $fixturesLeague
            ->filter(function ($fx) use ($roundId) {
                $rid = (int) (data_get($fx, 'round_id') ?? data_get($fx, 'round.id') ?? 0);
                return $rid === (int) $roundId;
            })
            ->sortBy('starting_at')
            ->values()
            ->all();

        /** -----------------------------
         *  7) Standings
         * ------------------------------ */
        if ($seasonId > 0) {
            $standingsUrl = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participant;details.type;form";

            $standingsRes = $this->curlGet($standingsUrl);

            if ($standingsRes['ok']) {
                $standings = data_get($standingsRes, 'json.data', []) ?? [];
            } else {
                $standingsErr = $standingsRes['error'] ?? null;
            }
        }

        /** -----------------------------
         *  8) Players (Squad) — أول 6 فرق
         * ------------------------------ */
        try {
            // 1) teamIds من standings (robust)
            $teamIds = collect($standings)
                ->flatMap(function ($row) {
                    $ids = collect();

                    // شكل 1: participant_id مباشرة
                    $pid = data_get($row, 'participant_id');
                    if ($pid) $ids->push($pid);

                    // شكل 2: participant.id
                    $pid2 = data_get($row, 'participant.id');
                    if ($pid2) $ids->push($pid2);

                    // شكل 3: داخل standings/data (nested)
                    $inner = data_get($row, 'standings', data_get($row, 'data', []));
                    if (is_array($inner)) {
                        $ids = $ids->merge(collect($inner)->pluck('participant_id')->filter());
                        $ids = $ids->merge(collect($inner)->pluck('participant.id')->filter());
                    }

                    return $ids->all();
                })
                ->filter()
                ->unique()
                ->values();

            // 2) fallback: لو standings ما أعطانا شيء، خذ الفرق من fixtures (home/away)
            if ($teamIds->isEmpty() && !empty($fixturesLeague)) {
                $teamIds = collect($fixturesLeague)
                    ->take(40) // لا توسّع كثير
                    ->flatMap(function ($fx) {
                        $parts = collect(data_get($fx, 'participants.data', data_get($fx, 'participants', [])));

                        $home = $parts->firstWhere('meta.location', 'home');
                        $away = $parts->firstWhere('meta.location', 'away');

                        $ids = [
                            data_get($home, 'id'),
                            data_get($away, 'id'),
                        ];

                        return array_filter($ids);
                    })
                    ->unique()
                    ->values();
            }

            // 3) خذ أول 6 فقط
            $teamIds = $teamIds->take(6)->values();

            foreach ($teamIds as $teamId) {
                $teamUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}"
                    . "?api_token={$token}&locale={$locale}"
                    . "&include=squad;venue;country";

                $teamRes = $this->curlGet($teamUrl);
                if (!$teamRes['ok']) continue;

                $team = data_get($teamRes, 'json.data');
                if (!$team) continue;

                $squad = data_get($team, 'squad.data', data_get($team, 'squad', []));
                if (!is_array($squad)) $squad = [];

                $playersBlocks[] = [
                    'team'  => (string) data_get($team, 'name', ''),
                    'logo'  => (string) data_get($team, 'image_path', ''),
                    'squad' => $squad,
                ];
            }
        } catch (\Throwable $e) {
            $playersErr = $e->getMessage();
        }


        /** -----------------------------
         *  9) Season Statistics
         * ------------------------------ */
        if ($seasonId > 0) {
            $seasonUrl = "https://api.sportmonks.com/v3/football/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}&include=statistics";

            $seasonRes = $this->curlGet($seasonUrl);

            if ($seasonRes['ok']) {
                $seasonStats = data_get(
                    $seasonRes,
                    'json.data.statistics.data',
                    data_get($seasonRes, 'json.data.statistics', [])
                ) ?? [];
            } else {
                $seasonStatsErr = $seasonRes['error'] ?? null;
            }
        }

        /** -----------------------------
         *  10) Topscorers
         * ------------------------------ */
        if ($seasonId > 0) {
            $topUrl = "https://api.sportmonks.com/v3/football/topscorers/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=player;participant;type;stage"
                . "&per_page=50";

            $topRes = $this->curlGet($topUrl);

            if ($topRes['ok']) {
                $topscorers = data_get($topRes, 'json.data', []) ?? [];
            } else {
                $topscorersErr = $topRes['error'] ?? null;
            }
        }

        return view('frontEnd.custom.league-details', [
            'leagueId' => $leagueId,
            'seasonId' => $seasonId,
            'locale'   => $locale,

            'league' => $league,

            // ✅ matchday
            'round' => $round,
            'roundId' => $roundId,
            'matchdayNumber' => $matchdayNumber,
            'totalRounds' => $totalRounds,
            'prevRoundId' => $prevRoundId,
            'nextRoundId' => $nextRoundId,

            // ✅ fixtures للجولة الحالية فقط
            'fixtures'    => $fixtures,
            'fixturesErr' => $fixturesErr,
            'roundsErr'   => $roundsErr,

            // ✅ نفس المتغيرات
            'standings'    => $standings,
            'standingsErr' => $standingsErr,

            'playersBlocks' => $playersBlocks,
            'playersErr'    => $playersErr,

            'seasonStats'    => $seasonStats,
            'seasonStatsErr' => $seasonStatsErr,

            'topscorers'    => $topscorers,
            'topscorersErr' => $topscorersErr,

            'leagueErr' => $leagueRes['ok'] ? null : ($leagueRes['error'] ?? null),
        ]);
    }

    /**
     * ✅ اختيار الجولة الافتراضية الذكي:
     * LIVE > Today > Finished > Scheduled (أقرب مباراة قادمة)
     */
    private function pickSmartDefaultRoundId($fixturesLeague): int
    {
        $now = now();
        $today = now()->toDateString();

        $groups = collect($fixturesLeague)
            ->filter(fn($fx) => data_get($fx, 'starting_at'))
            ->groupBy(function ($fx) {
                return (int) (data_get($fx, 'round_id') ?? data_get($fx, 'round.id') ?? 0);
            })
            ->filter(fn($fixtures, $rid) => (int)$rid > 0);

        if ($groups->isEmpty()) return 0;

        $ranked = $groups->map(function ($fixtures, $rid) use ($now, $today) {
            $fixtures = collect($fixtures);

            $hasLive = $fixtures->contains(function ($fx) {
                $code = strtolower((string) data_get($fx, 'state.code', ''));
                $name = strtolower((string) data_get($fx, 'state.name', ''));
                return str_contains($code, 'live') || str_contains($code, 'inplay') || str_contains($name, 'live') || str_contains($name, 'مباشر');
            });

            $hasToday = $fixtures->contains(function ($fx) use ($today) {
                $start = data_get($fx, 'starting_at');
                if (!$start) return false;
                return Carbon::parse($start)->toDateString() === $today;
            });

            $hasFinished = $fixtures->contains(function ($fx) {
                $code = strtolower((string) data_get($fx, 'state.code', ''));
                $name = strtolower((string) data_get($fx, 'state.name', ''));
                return str_contains($code, 'finished') || str_contains($code, 'ft') || str_contains($name, 'finished') || str_contains($name, 'انتهت') || str_contains($name, 'النه');
            });

            // أقرب مباراة قادمة
            $nextKickoff = $fixtures
                ->map(fn($fx) => Carbon::parse(data_get($fx, 'starting_at')))
                ->filter(fn($d) => $d->gte($now))
                ->sort()
                ->first();

            // آخر مباراة (للاحتياط)
            $lastKickoff = $fixtures
                ->map(fn($fx) => Carbon::parse(data_get($fx, 'starting_at')))
                ->sort()
                ->last();

            // priority: LIVE(4) > Today(3) > Finished(2) > Scheduled(1)
            $priority = $hasLive ? 4 : ($hasToday ? 3 : ($hasFinished ? 2 : 1));

            return [
                'round_id' => (int) $rid,
                'priority' => $priority,
                'next'     => $nextKickoff,
                'last'     => $lastKickoff,
            ];
        });

        // ✅ نختار حسب:
        // 1) أعلى priority
        // 2) لو Scheduled: الأقرب (next)
        // 3) غير كذا: الأحدث (last)
        $best = $ranked
            ->sort(function ($a, $b) use ($now) {
                // priority desc
                if ($a['priority'] !== $b['priority']) return $b['priority'] <=> $a['priority'];

                // لو priority = 1 (Scheduled فقط): next asc
                if ($a['priority'] === 1) {
                    $an = $a['next'] ? $a['next']->timestamp : PHP_INT_MAX;
                    $bn = $b['next'] ? $b['next']->timestamp : PHP_INT_MAX;
                    return $an <=> $bn;
                }

                // غير ذلك: last desc
                $al = $a['last'] ? $a['last']->timestamp : 0;
                $bl = $b['last'] ? $b['last']->timestamp : 0;
                return $bl <=> $al;
            })
            ->first();

        return (int) data_get($best, 'round_id', 0);
    }



    /**
     * ✅ استخراج fixtures من schedule بشكل مرن
     */
    private function extractFixturesFromSchedule($scheduleData): array
    {
        $paths = [
            'fixtures.data',
            'fixtures',
            'rounds.data.*.fixtures.data',
            'rounds.*.fixtures.data',
            'stages.data.*.rounds.data.*.fixtures.data',
            'stages.*.rounds.*.fixtures.data',
        ];

        $all = collect();

        foreach ($paths as $path) {
            $chunk = data_get($scheduleData, $path);
            if (!$chunk) continue;
            $all = $all->merge(collect($chunk)->flatten(10));
        }

        if ($all->isEmpty() && is_array($scheduleData)) {
            $all = collect($scheduleData)->flatten(10);
        }

        return $all
            ->filter(fn($x) => is_array($x) && data_get($x, 'id'))
            ->unique('id')
            ->values()
            ->all();
    }









    private function curlGet(string $url): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $res = curl_exec($ch);

        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['ok' => false, 'error' => $err];
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($res, true);

        // ✅ اعتبره خطأ فقط لو كود HTTP يدل على خطأ أو errors موجودة
        if ($code >= 400) {
            $msg = data_get($json, 'message') ?: 'HTTP error';
            return ['ok' => false, 'http' => $code, 'error' => $msg, 'json' => $json];
        }

        if (data_get($json, 'errors')) {
            return ['ok' => false, 'http' => $code, 'error' => 'API errors', 'json' => $json];
        }

        return ['ok' => true, 'http' => $code, 'json' => $json];
    }




    private function pickCurrentSeasonId($league, int $fallbackSeasonId = 0): int
    {
        $seasons = collect(data_get($league, 'seasons.data', data_get($league, 'seasons', [])))
            ->filter(fn($s) => data_get($s, 'id'));

        if ($seasons->isEmpty()) return $fallbackSeasonId;

        $today = now()->startOfDay();

        $current = $seasons->first(function ($s) use ($today) {
            $start = data_get($s, 'starting_at');
            $end   = data_get($s, 'ending_at');
            if (!$start || !$end) return false;

            $startD = \Carbon\Carbon::parse($start)->startOfDay();
            $endD   = \Carbon\Carbon::parse($end)->endOfDay();

            return $today->between($startD, $endD);
        });

        if ($current) return (int) data_get($current, 'id');

        $upcoming = $seasons
            ->filter(fn($s) => data_get($s, 'starting_at'))
            ->map(function ($s) {
                $s['_start'] = \Carbon\Carbon::parse($s['starting_at'])->startOfDay();
                return $s;
            })
            ->filter(fn($s) => $s['_start']->gte(now()->startOfDay()))
            ->sortBy(fn($s) => $s['_start'])
            ->first();

        if ($upcoming) return (int) data_get($upcoming, 'id');

        $latest = $seasons->sortByDesc(function ($s) {
            return data_get($s, 'starting_at', data_get($s, 'id', 0));
        })->first();

        return (int) data_get($latest, 'id', $fallbackSeasonId);
    }



    public function round(Request $request, int $roundId)
    {
        $this->website_status();
        $token = '4XCcE7HqgnhHhHUosTNOEdjQJvM7EX63bJqH9U0aVUJ3sJ0w3qkmWR8MPiou';

        // نفس الفلاتر اللي في رابطك (تقدر تغيّرها من الواجهة)
        $marketId = (int) $request->get('market', 1);     // 1 = Fulltime Result
        $bookmakerId = (int) $request->get('bookmaker', 2); // 2 مثال (حسب اللي عندك)
        $locale = Helper::currentLanguage()->code; // إذا SportMonks يدعمها عندك

        $include = 'fixtures.odds.market;fixtures.odds.bookmaker;fixtures.participants;league.country';
        $filters = "markets:{$marketId};bookmakers:{$bookmakerId}";

        $url = "https://api.sportmonks.com/v3/football/standings/seasons/759"
            . "?api_token={$token}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);

            return view('frontEnd.custom.ucl', [
                'round' => null,
                'league' => null,
                'fixtures' => [],
                'selectedMarket' => $marketId,
                'selectedBookmaker' => $bookmakerId,
                'locale' => $locale,
                'error' => $error,
            ]);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $data = json_decode($response, true);

        // SportMonks أحيانًا يرجّع message حتى مع 200
        if (!empty($data['message'])) {
            return view('frontEnd.custom.ucl', [
                'round' => null,
                'league' => null,
                'fixtures' => [],
                'selectedMarket' => $marketId,
                'selectedBookmaker' => $bookmakerId,
                'locale' => $locale,
                'error' => $data['message'],
                'httpCode' => $httpCode,
            ]);
        }

        $round = $data['data'] ?? null;
        $league = $round['league'] ?? null;

        // حسب رد SportMonks ممكن تكون fixtures.data أو fixtures مباشرة
        $fixtures = data_get($round, 'fixtures.data', data_get($round, 'fixtures', []));

        return view('frontEnd.custom.ucl', [
            'round' => $round,
            'league' => $league,
            'fixtures' => $fixtures,
            'selectedMarket' => $marketId,
            'selectedBookmaker' => $bookmakerId,
            'locale' => $locale,
            'error' => null,
            'httpCode' => $httpCode,
        ]);
    }

    public function standings(Request $request)
    {
        $this->website_status();
        $token = '4XCcE7HqgnhHhHUosTNOEdjQJvM7EX63bJqH9U0aVUJ3sJ0w3qkmWR8MPiou';
        $seasonId = 759;

        $locale = $request->get('lang', 'ar'); // ar | en
        app()->setLocale(in_array($locale, ['ar', 'en']) ? $locale : 'en');

        // Includes مهمّة عشان الشعار + الأرقام + الفورمة
        $include = 'participant;details.type;form';

        $url = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
            . "?api_token={$token}"
            . "&include={$include}"
            . "&locale={$locale}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);

            return view('frontEnd.custom.standings', [
                'rows' => [],
                'error' => $error,
            ]);
        }

        curl_close($curl);
        $data = json_decode($response, true);

        if (!empty($data['message'])) {
            return view('frontEnd.custom.standings', [
                'rows' => [],
                'error' => $data['message'],
                'subscription' => $data['subscription'] ?? null,
            ]);
        }

        // غالبًا response يكون data array
        $rows = $data['data'] ?? [];
        $leagueId = data_get($rows, '0.league_id'); // 271
        $league = $this->getLeagueName($leagueId, $locale);


        return view('frontEnd.custom.standings', [
            'rows' => $rows,
            'leagueName' => $league['leagueName'],
            'leagueLogo' => $league['leagueLogo'],
            'error' => null,
        ]);
    }


    public function getLeagueName($leagueId, $locale)
    {
        $this->website_status();

        $token = '4XCcE7HqgnhHhHUosTNOEdjQJvM7EX63bJqH9U0aVUJ3sJ0w3qkmWR8MPiou';
        $leagueName = null;
        $leagueLogo = null;

        if ($leagueId) {
            $leagueUrl = "https://api.sportmonks.com/v3/football/leagues/{$leagueId}?api_token={$token}&locale={$locale}";

            $curl2 = curl_init();
            curl_setopt_array($curl2, [
                CURLOPT_URL => $leagueUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
            ]);

            $leagueRes = curl_exec($curl2);
            curl_close($curl2);

            $leagueJson = json_decode($leagueRes, true);
            $leagueName = data_get($leagueJson, 'data.name');
            $leagueLogo = data_get($leagueJson, 'data.image_path');
        }

        return [
            'leagueName' => $leagueName,
            'leagueLogo' => $leagueLogo,
        ];
    }

    public function showOld(Request $request, int $leagueId)
    {
        $this->website_status();
        $token = '4XCcE7HqgnhHhHUosTNOEdjQJvM7EX63bJqH9U0aVUJ3sJ0w3qkmWR8MPiou';
        $locale = $request->get('lang', 'ar');

        // الموسم (إذا عندك ثابت مثل 759 استخدمه، أو جيبه من API)
        $seasonId = (int) $request->get('season_id', 759);

        // --- Helper cURL ---
        $curlGet = function (string $url) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
            ]);
            $res = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($res === false) return ['ok' => false, 'error' => $err];
            return ['ok' => true, 'data' => json_decode($res, true)];
        };

        // 1) معلومات الدوري (لاسم/شعار)
        $leagueUrl = "https://api.sportmonks.com/v3/football/leagues/{$leagueId}?api_token={$token}&locale={$locale}";
        $leagueRes = $curlGet($leagueUrl);

        // 2) مباريات الموسم (fixtures) - خفيفة
        $fixturesUrl = "https://api.sportmonks.com/v3/football/fixtures"
            . "?api_token={$token}&locale={$locale}"
            . "&include=participants;league;state;scores"
            . "&per_page=100";
        $fixturesRes = $curlGet($fixturesUrl);

        $fixtures = collect(data_get($fixturesRes, 'data.data', []))
            ->where('league_id', $leagueId)
            ->where('season_id', $seasonId)
            ->values()
            ->all();

        // 3) الترتيب (standings)
        $standingsUrl = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=participant;details.type;form";
        $standingsRes = $curlGet($standingsUrl);

        $standings = data_get($standingsRes, 'data.data', []);

        // 4) اللاعبين (Squad) — يعتمد على توفره عندك
        // غالباً تجي من teams/{id}?include=squad، وهنا نحتاج team ids من standings أو fixtures
        $teamIds = collect($standings)
            ->pluck('participant.id')
            ->filter()
            ->unique()
            ->take(6) // خففها بالبداية
            ->values()
            ->all();

        $players = [];
        foreach ($teamIds as $teamId) {
            $teamUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=squad";
            $teamRes = $curlGet($teamUrl);

            $team = data_get($teamRes, 'data.data', null);
            if ($team) {
                $players[] = [
                    'team' => $team['name'] ?? '',
                    'logo' => $team['image_path'] ?? '',
                    'squad' => data_get($team, 'squad.data', data_get($team, 'squad', [])),
                ];
            }
        }

        // 5) إحصائيات (هنا بنحط placeholder)
        // تقدر لاحقًا تربطها بإحصائيات الموسم/الدوري حسب endpoints المتاحة باشتراكك
        $stats = [
            'note' => 'اربط هنا endpoint الإحصائيات المتاح في اشتراكك (مثل top scorers أو season stats).'
        ];

        return view('frontEnd.custom.show', [
            'league' => data_get($leagueRes, 'data.data', null),
            'fixtures' => $fixtures,
            'standings' => $standings,
            'players' => $players,
            'stats' => $stats,
            'leagueId' => $leagueId,
            'seasonId' => $seasonId,
            'locale' => $locale,
        ]);
    }




    public function website_status()
    {
        // Check the website Status
        if (!Auth::check()) {
            $site_status = Helper::GeneralSiteSettings("site_status");
            if ($site_status == 0) {
                echo view("frontEnd.closed", ["close_message" => Helper::GeneralSiteSettings("close_msg")])->render();
                exit();
            }
        }
    }
}
