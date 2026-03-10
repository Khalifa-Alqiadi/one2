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
use App\Services\ApiClientService;

class LeagueController extends Controller
{
    private string $baseUrl = 'https://api.sportmonks.com/v3/football';

    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }


    public function index()
    {
        $this->website_status();
        $leagues = \App\Models\League::with('country')->get();
        return view('frontEnd.custom.leagues', [
            'leagues' => $leagues,
            'count' => count($leagues),
        ]);
    }
    public function indexOld()
    {
        $this->website_status();
        $lang = request('lang', 'en'); // ar | en
        $locale = Helper::currentLanguage()->code; // إذا SportMonks يدعمها عندك

        $token = config('services.SPORTMONKS_TOKEN');


        $url = "https://api.sportmonks.com/v3/football/leagues"
            . "?api_token={$token}"
            . "&locale={$locale}";


        $leagueRes = $this->apiClient->curlGet($url);
        $data = data_get($leagueRes, 'json', []);

        // فلترة المباريات حسب الدوري
        $leagues = collect($data['data'] ?? [])
            ->map(function ($league) {

                $countryName = data_get($league, 'country.name');

                $seasons = collect(data_get($league, 'seasons', []));
                $foundedYear = $seasons
                    ->pluck('starting_at')            // أو جرّب 'year' لو هذا الموجود عندك
                    ->filter()
                    ->map(fn($d) => (int) substr($d, 0, 4))
                    ->sort()
                    ->first();

                return [
                    'id' => $league['id'] ?? null,
                    'name' => $league['name'] ?? null,
                    'league_image' => $league['image_path'] ?? null,
                    'country' => $countryName,
                    'founded_year_estimated' => $foundedYear, // “تقديري” من أقدم موسم
                ];
            })
            ->values()
            ->all();



        return view('frontEnd.custom.leagues', [
            'league' => 'UEFA Champions League',
            'leagues' => $leagues,
            'count' => count($leagues),
        ]);
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

        $leagueRes = $this->apiClient->curlGet($leagueUrl);
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

            $scheduleRes = $this->apiClient->curlGet($scheduleUrl);

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

            $roundsRes = $this->apiClient->curlGet($roundsUrl);

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

        // Decide whether we should force the visible round to the last one with fixtures.
        // Only do this when the user did NOT explicitly supply a `round_id` query param.
        $userProvidedRound = $request->query('round_id', null) !== null;

        // Make last visible round the last one that actually has fixtures.
        // Fall back to the true last round only if none of the rounds contain fixtures.
        if ($roundsCol->count() && !$userProvidedRound) {
            // Try to find the last round (in chronological order) that has at least one fixture
            $roundWithFixtures = $roundsCol->reverse()->first(function ($r) use ($fixturesLeague) {
                $rid = (int) data_get($r, 'id');
                return $fixturesLeague->first(function ($fx) use ($rid) {
                    $fxRid = (int) (data_get($fx, 'round_id') ?? data_get($fx, 'round.id') ?? 0);
                    return $fxRid === $rid;
                }) !== null;
            });

            $selectedRound = $roundWithFixtures ?: $roundsCol->last();

            $roundId = (int) data_get($selectedRound, 'id', $roundId);

            // Recompute current index and navigation ids
            $currentIndex = $roundsCol->search(fn($r) => (int) data_get($r, 'id') === (int) $roundId);
            if ($currentIndex === false) {
                $currentIndex = max(0, $roundsCol->count() - 1);
            }

            $matchdayNumber = $roundsCol->count() ? ($currentIndex + 1) : $matchdayNumber;
            $prevRoundId = $currentIndex > 0 ? (int) data_get($roundsCol->get($currentIndex - 1), 'id') : null;
            $nextRoundId = ($currentIndex < $roundsCol->count() - 1) ? (int) data_get($roundsCol->get($currentIndex + 1), 'id') : null;
            $round = $roundsCol->get($currentIndex) ?: $round;

            // Re-filter fixtures for the selected round
            $fixtures = collect($fixturesLeague)
                ->filter(function ($fx) use ($roundId) {
                    $rid = (int) (data_get($fx, 'round_id') ?? data_get($fx, 'round.id') ?? 0);
                    return $rid === (int) $roundId;
                })
                ->sortBy('starting_at')
                ->values()
                ->all();
        }

        // Enhance fixtures: compute minute and computed_is_live similar to FixturesController
        $fixtures = collect($fixtures)
            ->map(fn($fx) => Helper::enrichFixture(is_array($fx) ? $fx : (array) $fx))
            ->values()
            ->all();

        /** -----------------------------
         *  7) Standings
         * ------------------------------ */
        if ($seasonId > 0) {
            $standingsUrl = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participant;details.type;form";

            $standingsRes = $this->apiClient->curlGet($standingsUrl);

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

                $teamRes = $this->apiClient->curlGet($teamUrl);
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

            $seasonRes = $this->apiClient->curlGet($seasonUrl);

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

            $topRes = $this->apiClient->curlGet($topUrl);

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
     * ✅ يجمع كل الصفحات من between
     */
    private function fetchAllBetween(
        string $start,
        string $end,
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
            $url = "https://api.sportmonks.com/v3/football/fixtures/between/{$start}/{$end}"
                . "?api_token={$token}&locale={$locale}"
                . "&include={$includes}"
                . "&per_page={$perPage}"
                . "&page={$page}";

            $res = $this->apiClient->curlGet($url);

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

    public function liveProxy(Request $request)
    {
        $ids = collect(explode(',', (string)$request->query('ids')))
            ->filter()
            ->map(fn($v) => (int)$v)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['ok' => true, 'fixtures' => []]);
        }

        // 1) هات من DB أقل أعمدة
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

            // 2) قرار: هل نعتبرها Live حسب الوقت؟
            $isFinished = (bool)$fx->is_finished;
            $isTimeLive = $this->isFixtureTimeLive($fx->starting_at, $isFinished);

            if ($isFinished || !$isTimeLive) {
                // مش live => رجّع DB فقط
                $out[] = [
                    'id' => $fx->id,
                    'home_score' => $fx->home_score,
                    'away_score' => $fx->away_score,
                    'minute' => $fx->minute,
                    'is_live' => false,
                    'is_finished' => $isFinished,
                ];
                continue;
            }

            // 3) live => رجّع من API لكن مع cache قصير
            $cacheKey = "sportmonks:fixture_live:{$id}:{$locale}";
            $liveData = Cache::remember($cacheKey, 8, function () use ($id, $token, $locale) {
                return $this->fetchFixtureLiveFromSportmonks($id, $token, $locale);
            });

            // fallback لو فشل API: رجّع DB
            if (!$liveData) {
                $out[] = [
                    'id' => $fx->id,
                    'home_score' => $fx->home_score,
                    'away_score' => $fx->away_score,
                    'minute' => $fx->minute,
                    'is_live' => true, // حسب الوقت
                    'is_finished' => false,
                ];
                continue;
            }

            $out[] = $liveData;
        }

        return response()->json(['ok' => true, 'fixtures' => $out]);
    }

    /**
     * live window: من قبل البداية بـ 15 دقيقة إلى بعد البداية بـ 3 ساعات
     */
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

    /**
     * طلب SportMonks fixture live
     * يرجع: goals + minute + live flag
     */
    private function fetchFixtureLiveFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        // include time مهم للدقيقة
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;scores";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);

        $minute = data_get($match, 'time.minute'); // أحياناً يكون موجود حتى لو ما included time
        $minute = is_numeric($minute) ? (int)$minute : null;

        // state
        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name')
            ?? null;

        $stateCode = $this->normalizeStateCode($rawCode, $stateName);

        $isFinished = $this->isFinishedState($stateCode, $stateName, (string)data_get($match, 'result_info', ''));
        $isLive     = $this->isLiveState($stateCode, $stateName);

        return [
            'id' => (int) data_get($match, 'id'),
            'home_score' => is_numeric($homeScore) ? (int)$homeScore : null,
            'away_score' => is_numeric($awayScore) ? (int)$awayScore : null,
            'minute' => $minute,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'is_live' => $isLive && !$isFinished,
            'is_finished' => $isFinished,
        ];
    }









    // cURL logic moved to App\Services\ApiClientService




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
