<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\League;
use App\Models\Round;
use App\Models\Fixture;
use App\Services\ApiClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MatchesController extends Controller
{
    // =========================
    //  A) صفحة العرض من DB
    // =========================

    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
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
        // ✅ دور على الجولة الحالية
        $currentRound = (clone $baseQuery)->where('is_current', 1)->first();

        // ✅ إذا المستخدم ما مرر page، حوّله تلقائيًا لصفحة الجولة الحالية
        if (!$request->has('page') && $currentRound) {
            $countBefore = (clone $baseQuery)
                ->where('starting_at', '<', $currentRound->starting_at)
                ->count();

            $page = (int) floor($countBefore / $perPage) + 1;

            return redirect()->route('league.show', [
                'id' => $id,
                'season_id' => $seasonId,
                'page' => $page,
            ]);
        }

        // ✅ rounds + fixtures مرتبة من DB
        $rounds = $baseQuery
            ->with([
                'season',
                'fixtures' => function ($q) {
                    $q->orderBy('starting_at', 'asc');
                },
                'fixtures.homeTeam:id,name_ar,name_en,image_path',
                'fixtures.awayTeam:id,name_ar,name_en,image_path',
            ])
            ->paginate($perPage);

        // ✅ العدد الكلي الحقيقي
        $totalRounds = $rounds->total();

        // (اختياري) standings
        $standingsErr = null;

        $fixturesErr = null;
        $roundsErr = null;

        $seasons = $league->seasons()->orderByDesc('starting_at')->get();

        return view('frontEnd.custom.league-details', compact(
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
        ));
    }

    // =========================
    // B) Proxy live endpoint
    // (يجلب المباشر من SportMonks)
    // =========================
    public function liveProxy(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->filter()
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['ok' => true, 'fixtures' => []]);
        }

        $rows = Fixture::query()
            ->whereIn('id', $ids)
            ->select(['id', 'starting_at', 'is_finished', 'home_score', 'away_score', 'minute', 'state_name', 'state_code'])
            ->get()
            ->keyBy('id');

        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $out = [];

        foreach ($ids as $id) {
            $fx = $rows->get($id);
            if (!$fx) continue;

            $isFinishedDb = (bool) $fx->is_finished;
            // ✅ الوقت يستخدم فقط لتقرير هل نسوي polling أم لا
            $shouldPoll = $this->isFixtureTimeLive($fx->starting_at, false);

            // إذا المباراة منتهية في DB أو خارج نافذة المتابعة => رجّع DB فقط
            if ($isFinishedDb || !$shouldPoll) {
                $out[] = [
                    'id' => (int) $fx->id,
                    'home_score' => is_numeric($fx->home_score) ? (int)$fx->home_score : null,
                    'away_score' => is_numeric($fx->away_score) ? (int)$fx->away_score : null,
                    'minute' => is_numeric($fx->minute) ? (int)$fx->minute : null,
                    'state_code' => $fx->state_code,
                    'state_name' => $fx->state_name,
                    'is_finished' => $isFinishedDb,
                    'status' => $isFinishedDb ? 'FT' : 'NS',
                ];
                continue;
            }

            // ✅ Poll from API (مع كاش بسيط اختياري)
            $cacheKey = "sportmonks:fixture_live:{$id}:{$locale}";

            // $liveData = Cache::remember($cacheKey, 8, function () use ($id, $token, $locale) {
                $liveData = $this->fetchFixtureLiveFromSportmonks($id, $token, $locale);
            // });

            // ✅ إذا فشل API: لا تجبرها Live
            if (!$liveData) {
                $out[] = [
                    'id' => (int) $fx->id,
                    'home_score' => is_numeric($fx->home_score) ? (int)$fx->home_score : null,
                    'away_score' => is_numeric($fx->away_score) ? (int)$fx->away_score : null,
                    'minute' => is_numeric($fx->minute) ? (int)$fx->minute : null,
                    'state_code' => $fx->state_code,
                    'state_name' => $fx->state_name,
                    'is_finished' => $isFinishedDb,
                    'status' => $isFinishedDb ? 'FT' : 'UNK', // ✅ مجهول لكن لا تغيّر حالة المباراة
                ];
                continue;
            }

            // dd($liveData['status']);

            // ✅ حدّث DB فقط إذا API أكد FT
            if (($liveData['status'] ?? '') === 'FT' && $liveData['state_code'] === 'FT') {
                \App\Models\Fixture::where('id', $id)->update([
                    'home_score'    => $liveData['home_score'],
                    'away_score'    => $liveData['away_score'],
                    'ft_home_score' => $liveData['home_score'],
                    'ft_away_score' => $liveData['away_score'],
                    'is_finished'   => 1,
                    'state_code'    => $liveData['state_code'] ?? 'FT',
                    'state_name'    => $liveData['state_name'] ?? 'Finished',
                    'minute'        => null,
                ]);

                Cache::forget($cacheKey);
            }

            $out[] = $liveData;
        }

        return response()->json(['ok' => true, 'fixtures' => $out]);
    }

    // =========================
    // Helpers
    // =========================

    private function isFixtureTimeLive($startingAt, bool $isFinished): bool
    {
        if ($isFinished) return false;
        if (!$startingAt) return false;

        try {
            $start = \Carbon\Carbon::parse($startingAt);
        } catch (\Throwable $e) {
            return false;
        }

        $now = now();
        $from = $start->copy()->subMinutes(15);
        $to   = $start->copy()->addHours(3);

        return $now->between($from, $to);
    }

    private function fetchFixtureLiveFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;scores;periods;events";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        // scores
        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);

        // minute (اولاً time ثم periods ثم events)
        $minute =
            data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time')
            ?? null;

        if (!is_numeric($minute)) {
            $minute = $this->extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        if (!is_numeric($minute)) {
            $minute = $this->extractMinuteFromEvents((array) data_get($match, 'events', []));
        }

        $minute = is_numeric($minute) ? (int) $minute : null;

        // state
        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name')
            ?? null;

        $stateCode = $this->normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = $this->isFinishedState($stateCode, $stateName, $resultInfo);
        $isLive     = $this->isLiveState($stateCode, $stateName);

        $isTimeLive = $this->isFixtureTimeLive(data_get($match, 'starting_at'), $isFinished);

        $isLive = $isLive || $isTimeLive;
        // return [
        //     'id' => (int) data_get($match, 'id'),
        //     'home_score' => is_numeric($homeScore) ? (int) $homeScore : null,
        //     'away_score' => is_numeric($awayScore) ? (int) $awayScore : null,
        //     'minute' => $minute,
        //     'state_code' => $stateCode,
        //     'state_name' => $stateName,
        //     'is_live' => $isLive && !$isFinished,
        //     'is_finished' => $isFinished,
        // ];
        $status = 'NS';
        if ($isFinished) $status = 'FT';
        elseif ($this->isHalfTimeState($stateCode, $stateName)) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';

        return [
            'id' => (int) data_get($match, 'id'),
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'minute' => $minute,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'is_finished' => $isFinished,
            'status' => $status, // ✅ مهم
        ];
    }

    private function extractMinuteFromEvents(array $events): ?int
    {
        $events = is_array($events) ? $events : [];
        if (!$events) return null;

        // خذ آخر حدث له minute
        $last = collect($events)
            ->filter(fn($e) => is_numeric(data_get($e, 'minute')) || is_numeric(data_get($e, 'time.minute')))
            ->sortBy(function ($e) {
                return (int) (data_get($e, 'minute', data_get($e, 'time.minute', 0)));
            })
            ->last();

        $m = data_get($last, 'minute') ?? data_get($last, 'time.minute');
        return is_numeric($m) ? (int) $m : null;
    }

    // ✅ scores extractor قوي
    private function extractGoalsFromScores($scores): array
    {
        $scores = is_array($scores) ? $scores : [];
        $col = collect($scores)->sort();

        // CURRENT
        $current = $col->first(function ($s) {
            $typeCode = strtoupper((string) data_get($s, 'type.code', data_get($s, 'type', '')));
            $desc     = strtoupper((string) data_get($s, 'description', ''));
            return $typeCode === 'CURRENT' || str_contains($desc, 'CURRENT');
        });

        // FT
        $ft = $col->first(function ($s) {
            $typeCode = strtoupper((string) data_get($s, 'type.code', data_get($s, 'type', '')));
            $desc     = strtoupper((string) data_get($s, 'description', ''));
            return $typeCode === 'FT' || $typeCode === 'FULLTIME' || str_contains($desc, 'FT');
        });

        $row = $current ?: $ft;

        $home = data_get($row, 'score.home')
            ?? data_get($row, 'score.goals.home')
            ?? data_get($row, 'score.goals_home')
            ?? null;

        $away = data_get($row, 'score.away')
            ?? data_get($row, 'score.goals.away')
            ?? data_get($row, 'score.goals_away')
            ?? null;

        // fallback participant
        if ($home === null || $away === null) {
            $homeRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'home');
            $awayRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'away');

            $home = $home ?? data_get($homeRow, 'score.goals');
            $away = $away ?? data_get($awayRow, 'score.goals');
        }

        $home = is_numeric($home) ? (int)$home : null;
        $away = is_numeric($away) ? (int)$away : null;

        return [$home, $away];
    }

    private function extractMinuteFromPeriods(array $periods): ?int
    {
        $periods = is_array($periods) ? $periods : [];
        if (!$periods) return null;

        $col = collect($periods);

        // جرّب تجيب الـ current / inplay period
        $current = $col->first(function ($p) {
            $desc = mb_strtolower((string) data_get($p, 'description', ''));
            $type = mb_strtolower((string) data_get($p, 'type', data_get($p, 'type.code', '')));
            $isCurrent = (bool) data_get($p, 'is_current', false);

            return $isCurrent
                || str_contains($desc, 'current')
                || str_contains($desc, 'in play')
                || str_contains($type, 'current')
                || str_contains($type, 'inplay');
        });

        // لو ما وجد current خذ آخر فترة
        $p = $current ?: $col->sortBy(function ($x) {
            return (int) (data_get($x, 'sort_order', data_get($x, 'id', 0)));
        })->last();

        // بعضهم يرجع minute مباشرة
        $m =
            data_get($p, 'minute')
            ?? data_get($p, 'time.minute')
            ?? data_get($p, 'minutes')
            ?? null;

        // أو يحسبها من start/end (ثواني)
        // أحيانًا يكون start/end شكل: "00:12:34" أو "12:34"
        if (!is_numeric($m)) {
            $end = data_get($p, 'ended', data_get($p, 'end', data_get($p, 'ended_at')));
            if (is_numeric($end)) {
                $m = floor(((int)$end) / 60);
            }
        }

        return is_numeric($m) ? (int) $m : null;
    }

    private function normalizeStateCode($rawCode, string $stateName): ?string
    {
        $code = strtoupper(trim((string) $rawCode));
        $name = mb_strtolower(trim($stateName));

        if ($code === '' || $code === 'NULL') {
            if (
                str_contains($name, 'نهاية') || str_contains($name, 'انته') || str_contains($name, 'نهائ')
                || str_contains($name, 'finished') || str_contains($name, 'full time')
            ) return 'FT';

            if (str_contains($name, 'مباشر') || str_contains($name, 'live') || str_contains($name, 'in play') || str_contains($name, 'inplay')) return 'LIVE';

            if (str_contains($name, 'الشوط') || str_contains($name, 'half')) return 'HT';

            if (str_contains($name, 'لم تبدأ') || str_contains($name, 'not started') || str_contains($name, 'ns')) return 'NS';

            return null;
        }

        $code = str_replace([' ', '-'], '_', $code);

        $map = [
            'FINISHED' => 'FT',
            'FULLTIME' => 'FT',
            'FULL_TIME' => 'FT',
            'INPLAY' => 'LIVE',
            'IN_PLAY' => 'LIVE',
            'PLAYING' => 'LIVE',
            'HALFTIME' => 'HT',
            'HALF_TIME' => 'HT',
            'NOTSTARTED' => 'NS',
            'NOT_STARTED' => 'NS',
            'AET' => 'ET',
            'EXTRATIME' => 'ET',
            'EXTRA_TIME' => 'ET',
            'PENALTIES' => 'PEN',
            'POSTPONED' => 'POSTP',
            'CANCELLED' => 'CANC',
            'CANCELED' => 'CANC',
            'SUSPENDED' => 'SUSP',
            'ABANDONED' => 'ABD',
        ];

        $code = $map[$code] ?? $code;

        if (strlen($code) > 10) $code = substr($code, 0, 10);

        return $code ?: null;
    }

    private function isLiveState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        // الحالات اللي تعتبر لعب فعلي
        if (in_array($code, ['LIVE', 'INPLAY', '1H', '2H', 'ET', 'PEN'], true)) {
            return true;
        }

        // لو النص يدل على لعب مباشر
        if (
            str_contains($name, 'مباشر') ||
            str_contains($name, 'live') ||
            str_contains($name, 'in play') ||
            str_contains($name, 'inplay')
        ) {
            return true;
        }

        return false;
    }

    private function isHalfTimeState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        if (in_array($code, ['HT', 'HALFTIME', 'HALF_TIME', 'BREAK'], true)) {
            return true;
        }

        if (
            str_contains($name, 'half') ||
            str_contains($name, 'منتصف') ||
            str_contains($name, 'استراحة')
        ) {
            return true;
        }

        return false;
    }

    private function isFinishedState(?string $stateCode, string $stateName, string $resultInfo): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);
        $resultInfo = trim($resultInfo);

        if (in_array($code, ['FT', 'CANC', 'ABD', 'SUSP'], true)) return true;

        $isFinishedByText =
            str_contains($name, 'finished') ||
            str_contains($name, 'full') ||
            str_contains($name, 'ended') ||
            str_contains($name, 'final') ||
            str_contains($name, 'انته') ||
            str_contains($name, 'نهائ') ||
            str_contains($name, 'نهاية') ||
            str_contains($name, 'مكتمل');

        $isFinishedByResult = $resultInfo !== '';

        return $isFinishedByText || $isFinishedByResult;
    }

    private function pickSmartRoundIdFromDb(int $leagueId, int $seasonId): int
    {
        $now = now();

        $fx = Fixture::query()
            ->where('league_id', $leagueId)
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->where('is_finished', 0)
            ->whereNotNull('starting_at')
            ->orderBy('starting_at')
            ->get()
            ->first(function ($f) use ($now) {
                try {
                    $start = \Carbon\Carbon::parse($f->starting_at);
                    return $now->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                } catch (\Throwable $e) {
                    return false;
                }
            });

        if ($fx && $fx->round_id) return (int)$fx->round_id;

        $next = Fixture::query()
            ->where('league_id', $leagueId)
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->whereNotNull('starting_at')
            ->orderBy('starting_at')
            ->first();

        return (int) ($next->round_id ?? 0);
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
        $ttlSeconds = (data_get($cached, 'status') === 'LIVE') ? 15 : 3600;
        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->find($id);
        $data = Cache::remember($cacheKey, $ttlSeconds, function () use ($id, $token, $locale) {
            return $this->fetchFixtureDetailsFromSportmonks($id, $token, $locale);
        });

        if (!$data) {
            return view('frontEnd.custom.match-show', [
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

        return view('frontEnd.custom.match-show', [
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
            'time' => now()->toDateTimeString(),
        ]);
    }

    private function fetchFixtureDetailsFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;participants;scores;events;events.player;events.type;lineups;lineups.player;lineups.position;statistics.type;metadata;odds";

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
        [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);



        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name');



        $stateCode = $this->normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = $this->isFinishedState($stateCode, $stateName, $resultInfo);
        $isHalf     = $this->isHalfTimeState($stateCode, $stateName);
        $isLive     = $this->isLiveState($stateCode, $stateName);

        $status = 'NS';
        if ($isFinished) $status = 'FT';
        elseif ($isHalf) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';

        $eventsRaw = (array) data_get($match, 'events', []);
        $events = $this->normalizeEvents($eventsRaw, $homeId, $awayId);

        // $minute = data_get($match, 'time.minute') ?? data_get($match, 'time.current_minute');
        // $minute = is_numeric($minute) ? (int)$minute : null;

        // if ($minute === null) {
            $minute = collect($eventsRaw)->pluck('minute')->filter()->max();
            $minute = is_numeric($minute) ? (int)$minute : null;
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
            'minute' => in_array($status, ['LIVE','HT'], true) ? $minute : null,

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

    private function normalizeEvents(array $events, int $homeId, int $awayId): array
    {
        return collect($events)
            ->filter(fn($e) => is_array($e))
            ->map(function ($e) use ($homeId, $awayId) {

                // minute label
                $minute = (int) (data_get($e, 'minute') ?? 0);
                $extra  = data_get($e, 'extra_minute');
                $minuteLabel = $minute > 0
                    ? ($minute . (is_numeric($extra) && (int)$extra > 0 ? '+' . (int)$extra : '') . "'")
                    : '';

                // side
                $pid  = (int) (data_get($e, 'participant_id') ?? 0);
                $side = ($pid === $homeId) ? 'home' : (($pid === $awayId) ? 'away' : 'neutral');

                // type
                $typeCode = strtolower((string)(
                    data_get($e, 'type.code')
                    ?? data_get($e, 'type.developer_name')
                    ?? data_get($e, 'type_code')
                    ?? ''
                ));

                $typeName = (string)(
                    data_get($e, 'type.name')
                    ?? data_get($e, 'name')
                    ?? ''
                );

                // players (الأسماء عندك جاهزة)
                $playerName        = (string)(data_get($e, 'player_name') ?? data_get($e, 'player.display_name') ?? data_get($e, 'player.name') ?? '');
                $relatedPlayerName = (string)(data_get($e, 'related_player_name') ?? data_get($e, 'related_player.display_name') ?? data_get($e, 'related_player.name') ?? '');

                $playerImg  = (string)(data_get($e, 'player.image_path') ?? '');
                $relatedImg = (string)(data_get($e, 'related_player.image_path') ?? '');

                // detect kinds
                $isGoal = in_array($typeCode, ['goal', 'penalty', 'own_goal'], true);
                $isSub  = str_contains($typeCode, 'sub'); // substitution/sub
                $isCard = in_array($typeCode, ['yellowcard', 'yellow_card', 'redcard', 'red_card', 'secondyellow', 'second_yellow'], true)
                    || str_contains($typeCode, 'card');

                // build structure for UI
                if ($isSub) {
                    // SportMonks: غالبًا player = OUT و related = IN (بس أحيانًا العكس)
                    $outName = $playerName;
                    $inName  = $relatedPlayerName;

                    $outImg = $playerImg;
                    $inImg  = $relatedImg;

                    // fallback swap لو واضح العكس (لو out فاضي و in موجود)
                    if ($outName === '' && $inName !== '') {
                        [$outName, $inName] = [$inName, $outName];
                        [$outImg,  $inImg] = [$inImg,  $outImg];
                    }

                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'sub',
                        'label' => 'تبديل لاعب',
                        'sub' => [
                            'in' => [
                                'name' => $inName,
                                'image' => $inImg,
                                'number' => null, // إذا عندك endpoint lineups نقدر نملأه
                                'pos' => null,
                            ],
                            'out' => [
                                'name' => $outName,
                                'image' => $outImg,
                                'number' => null,
                                'pos' => null,
                            ],
                        ],
                        'raw' => $e,
                    ];
                }

                if ($isGoal) {
                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'goal',
                        'label' => $typeName ?: 'هدف',
                        'goal' => [
                            'scorer_name'  => $playerName,
                            'scorer_image' => $playerImg,
                            'assist_name'  => $relatedPlayerName, // أحيانًا تكون assist وأحيانًا null
                            'scoreline'    => (string)(data_get($e, 'result') ?? ''), // "1-0"
                            'info'         => (string)(data_get($e, 'info') ?? ''),
                            'addition'     => (string)(data_get($e, 'addition') ?? ''), // "1st Goal"
                        ],
                        'raw' => $e,
                    ];
                }

                if ($isCard) {
                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'card',
                        'label' => $typeName ?: 'بطاقة',
                        'card' => [
                            'player_name' => $playerName,
                            'player_image' => $playerImg,
                            'type_code' => $typeCode,
                        ],
                        'raw' => $e,
                    ];
                }

                return [
                    'minute' => $minute,
                    'minute_label' => $minuteLabel,
                    'side' => $side,
                    'kind' => 'other',
                    'label' => $typeName ?: 'حدث',
                    'raw' => $e,
                ];
            })
            // لو تبي كل الأحداث لا تحذف شيء
            // ->filter(fn($x) => in_array($x['kind'], ['sub','goal','card'], true))
            ->sortBy(fn($x) => (int)($x['minute'] ?? 0))
            ->values()
            ->all();
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
