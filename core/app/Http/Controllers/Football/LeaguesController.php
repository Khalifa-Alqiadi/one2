<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Round;
use App\Models\Standing;
use App\Services\ApiClientService;
use App\Services\SportmonksStandingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LeaguesController extends Controller
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }


    public function index()
    {
        $this->website_status();
        $leagues = \App\Models\League::with('country')
            ->orderBy('row_no', 'asc')
            ->get();
        return view('frontEnd.football.leagues', [
            'leagues' => $leagues,
            'count' => count($leagues),
        ]);
    }

    public function rounds(Request $request, $id = null)
    {
        $leagueId = (int) $id;
        $league = League::findOrFail($leagueId);


        $locale = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';

        // Resolve the requested season with a safe fallback.
        $season = $this->resolveLeagueSeason($league, (int) $request->query('season_id', 0));
        $seasonId = $season ? (int) $season->id : 0;

        // ✅ standings من API مع كاش ساعة
        $standings = [];
        $standingsUpdatedAt = null;
        $standingsErr = null;

        if ($seasonId > 0 && $request->boolean('refresh_standings')) {
            $this->refreshStandingsCache($seasonId, $locale);
        }

        if ($seasonId > 0) {
            $standingsData = $this->getStandingsCached($seasonId, $locale);

            $standings = $standingsData['standings'] ?? [];
            $standingsUpdatedAt = $standingsData['fetched_at'] ?? null;
            $standingsErr = $standingsData['error'] ?? null;
        }

        $rounds = $this->leagueRoundsQuery($leagueId, $seasonId)->get();
        $roundsCount = $rounds->count();

        $totalRounds = 0;
        // (اختياري) standings
        $fixturesErr = null;
        $roundsErr = null;

        $stages = $this->leagueStagesQuery($league, $seasonId)->get();

        $pages = collect();
        $name_var = 'name_' . Helper::currentLanguage()->code;
        foreach ($stages as $stage) {
            $stageName = mb_strtolower((string) ($stage->name ?? ''));
            if (count($stage->rounds) > 0) {

                // اعتبر هذه المرحلة الأساسية لو عندها عدة جولات
                $isLeaguePhase = $stage->rounds->count() > 1;

                if ($isLeaguePhase) {
                    $roundsCount = $stage->rounds->count();
                    foreach ($stage->rounds as $round) {
                        $fixtures = $round->fixtures
                            ->sortByDesc(fn($fixture) => $fixture->starting_at?->timestamp ?? 0)
                            ->values();

                        $pages->push([
                            'type' => 'round',
                            'title' => __('frontend.matchday_progress', [
                                'current' => $round->name,
                                'total' => $roundsCount,
                            ]),
                            'stage' => $stage,
                            'round' => $round,
                            'fixtures' => $fixtures,
                        ]);
                    }
                } else {
                    // المراحل الإقصائية كصفحة واحدة
                    $fixtures = $stage->rounds
                        ->flatMap(fn($round) => $round->fixtures)
                        ->sortByDesc('starting_at')
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
                $fixtures = $stage->fixtures
                    ->sortByDesc(fn($fixture) => $fixture->starting_at?->timestamp ?? 0)
                    ->values();

                $pages->push([
                    'type' => 'stage',
                    'title' => $stage->$name_var ?: ('Stage ' . $stage->id),
                    'stage' => $stage,
                    'round' => null,
                    'fixtures' => $fixtures,
                ]);
            }
        }
        $roundsCount = $rounds->count();

        if ($pages->isEmpty() || !$pages->contains(fn($page) => collect($page['fixtures'] ?? [])->isNotEmpty())) {
            $roundPages = $this->buildRoundPages($rounds);

            if ($roundPages->isNotEmpty()) {
                $pages = $roundPages;
            }
        }

        $pages = $this->appendLooseFixturePage($pages, $leagueId, $seasonId);

        $perPage = 1;
        $currentPage = max(1, LengthAwarePaginator::resolveCurrentPage());
        if ($pages->isNotEmpty()) {
            $currentPage = min($currentPage, $pages->count());
        }
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

        if (!$request->has('page') && $pages->isNotEmpty()) {
            $routeParams = [
                'id' => $id,
                'page' => $this->findTargetPageIndex($pages) + 1,
            ];

            if ($seasonId > 0) {
                $routeParams['season_id'] = $seasonId;
            }

            return redirect()->route('league.rounds', $routeParams);
        }

        $seasons = $league->seasons()->orderByDesc('starting_at')->get();

        return view('frontEnd.football.rounds', compact(
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
    private function resolveLeagueSeason(League $league, int $requestedSeasonId)
    {
        if ($requestedSeasonId > 0) {
            $season = $league->seasons()->where('id', $requestedSeasonId)->first();

            if ($season) {
                return $season;
            }
        }

        return $league->seasons()
            ->where('is_current', 1)
            ->orderByDesc('starting_at')
            ->orderByDesc('id')
            ->first()
            ?: $league->seasons()
                ->orderByDesc('starting_at')
                ->orderByDesc('id')
                ->first();
    }

    private function leagueRoundsQuery(int $leagueId, int $seasonId)
    {
        return Round::query()
            ->where('league_id', $leagueId)
            ->when($seasonId > 0, fn($q) => $q->where('season_id', $seasonId))
            ->with([
                'stage',
                'fixtures' => function ($fx) use ($seasonId) {
                    $fx->when($seasonId > 0, fn($q) => $q->where('season_id', $seasonId))
                        ->with(['homeTeam', 'awayTeam'])
                        ->orderBy('starting_at', 'desc')
                        ->orderBy('id', 'desc');
                },
            ])
            ->orderBy('starting_at', 'asc')
            ->orderBy('id', 'asc');
    }

    private function leagueStagesQuery(League $league, int $seasonId)
    {
        return $league->stages()
            ->when($seasonId > 0, fn($q) => $q->where('season_id', $seasonId))
            ->with([
                'rounds' => function ($q) use ($seasonId) {
                    $q->when($seasonId > 0, fn($rounds) => $rounds->where('season_id', $seasonId))
                        ->with([
                            'fixtures' => function ($fx) use ($seasonId) {
                                $fx->when($seasonId > 0, fn($fixtures) => $fixtures->where('season_id', $seasonId))
                                    ->with(['homeTeam', 'awayTeam'])
                                    ->orderBy('starting_at', 'desc')
                                    ->orderBy('id', 'desc');
                            },
                        ])
                        ->orderBy('starting_at', 'asc')
                        ->orderBy('id', 'asc');
                },
                'fixtures' => function ($fx) use ($seasonId) {
                    $fx->when($seasonId > 0, fn($fixtures) => $fixtures->where('season_id', $seasonId))
                        ->with(['homeTeam', 'awayTeam'])
                        ->orderBy('starting_at', 'desc')
                        ->orderBy('id', 'desc');
                },
            ])
            ->orderBy('sort_order', 'asc')
            ->orderBy('starting_at', 'asc')
            ->orderBy('id', 'asc');
    }

    private function buildRoundPages($rounds)
    {
        $pages = collect();
        $roundsCount = max(1, $rounds->count());

        foreach ($rounds as $round) {
            $pages->push([
                'type' => 'round',
                'title' => __('frontend.matchday_progress', [
                    'current' => $round->name ?: $round->id,
                    'total' => $roundsCount,
                ]),
                'stage' => $round->stage,
                'round' => $round,
                'fixtures' => $this->sortFixtures($round->fixtures),
            ]);
        }

        return $pages;
    }

    private function appendLooseFixturePage($pages, int $leagueId, int $seasonId)
    {
        $shownFixtureIds = $pages
            ->flatMap(fn($page) => collect($page['fixtures'] ?? [])->pluck('id'))
            ->filter()
            ->unique()
            ->values();

        $query = Fixture::query()
            ->where('league_id', $leagueId)
            ->when($seasonId > 0, fn($q) => $q->where('season_id', $seasonId))
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('starting_at', 'desc')
            ->orderBy('id', 'desc');

        if ($shownFixtureIds->isNotEmpty()) {
            $query->whereNotIn('id', $shownFixtureIds->all());
        }

        $fixtures = $query->get();

        if ($fixtures->isNotEmpty()) {
            $pages->push([
                'type' => 'fixtures',
                'title' => __('frontend.matches'),
                'stage' => null,
                'round' => null,
                'fixtures' => $this->sortFixtures($fixtures),
            ]);
        }

        return $pages;
    }

    private function sortFixtures($fixtures)
    {
        return collect($fixtures)
            ->filter()
            ->unique('id')
            ->sortByDesc(fn($fixture) => $this->fixtureTimestamp($fixture) ?? 0)
            ->values();
    }

    private function findTargetPageIndex($pages): int
    {
        $hasFixturePages = $pages->contains(
            fn($page) => collect($page['fixtures'] ?? [])->isNotEmpty()
        );

        foreach ($pages as $index => $page) {
            if ($this->pageHasCurrentFlag($page) && (!$hasFixturePages || collect($page['fixtures'] ?? [])->isNotEmpty())) {
                return (int) $index;
            }
        }

        foreach ($pages as $index => $page) {
            if (collect($page['fixtures'] ?? [])->contains(fn($fixture) => $this->fixtureLooksLive($fixture))) {
                return (int) $index;
            }
        }

        $now = now()->timestamp;
        $upcomingIndex = null;
        $upcomingTimestamp = null;
        $latestIndex = null;
        $latestTimestamp = null;

        foreach ($pages as $index => $page) {
            foreach (collect($page['fixtures'] ?? []) as $fixture) {
                $timestamp = $this->fixtureTimestamp($fixture);

                if (!$timestamp) {
                    continue;
                }

                if (!$fixture->is_finished && $timestamp >= $now && ($upcomingTimestamp === null || $timestamp < $upcomingTimestamp)) {
                    $upcomingTimestamp = $timestamp;
                    $upcomingIndex = (int) $index;
                }

                if ($latestTimestamp === null || $timestamp > $latestTimestamp) {
                    $latestTimestamp = $timestamp;
                    $latestIndex = (int) $index;
                }
            }
        }

        return $upcomingIndex ?? $latestIndex ?? 0;
    }

    private function pageHasCurrentFlag(array $page): bool
    {
        return (bool) data_get($page, 'round.is_current') || (bool) data_get($page, 'stage.is_current');
    }

    private function fixtureLooksLive($fixture): bool
    {
        if ((bool) $fixture->is_finished) {
            return false;
        }

        $state = strtoupper((string) ($fixture->state_code ?? $fixture->state_name ?? ''));
        if (in_array($state, ['LIVE', 'HT', 'INPLAY_1ST', 'INPLAY_2ND'], true)) {
            return true;
        }

        $timestamp = $this->fixtureTimestamp($fixture);

        return $timestamp
            && $timestamp >= now()->subMinutes(15)->timestamp
            && $timestamp <= now()->addHours(3)->timestamp;
    }

    private function fixtureTimestamp($fixture): ?int
    {
        if (!$fixture || !$fixture->starting_at) {
            return null;
        }

        try {
            if ($fixture->starting_at instanceof \DateTimeInterface) {
                return $fixture->starting_at->getTimestamp();
            }

            return \Carbon\Carbon::parse($fixture->starting_at)->timestamp;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function roundsOld(Request $request, $id = null)
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

        $standingsPack = null;
        $standingsErr = null;

        $standingsData = $this->getStandingsCached($seasonId, $locale);

        $standings = $standingsData['standings'] ?? [];
        $standingsUpdatedAt = $standingsData['fetched_at'] ?? null;

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
                        ->with([
                            'fixtures' => function ($fx) {
                                $fx->with(['homeTeam', 'awayTeam'])
                                    ->orderBy('starting_at', 'desc')
                                    ->orderBy('id', 'desc');
                            },
                        ])
                        ->orderBy('starting_at', 'asc');
                },
                'fixtures' => function ($fx) {
                    $fx->with(['homeTeam', 'awayTeam'])
                        ->orderBy('starting_at', 'desc')
                        ->orderBy('id', 'desc');
                },
            ])
            ->where('season_id', $seasonId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('starting_at', 'asc')
            ->get();

        $pages = collect();
        $name_var = 'name_' . Helper::currentLanguage()->code;
        foreach ($stages as $stage) {
            $stageName = mb_strtolower((string) ($stage->name ?? ''));
            if (count($stage->rounds) > 0) {

                // اعتبر هذه المرحلة الأساسية لو عندها عدة جولات
                $isLeaguePhase = $stage->rounds->count() > 1;

                if ($isLeaguePhase) {
                    $roundsCount = $stage->rounds->count();
                    foreach ($stage->rounds as $round) {
                        $fixtures = $round->fixtures
                            ->sortByDesc(fn($fixture) => $fixture->starting_at?->timestamp ?? 0)
                            ->values();

                        $pages->push([
                            'type' => 'round',
                            'title' => __('frontend.matchday_progress', [
                                'current' => $round->name,
                                'total' => $roundsCount,
                            ]),
                            'stage' => $stage,
                            'round' => $round,
                            'fixtures' => $fixtures,
                        ]);
                    }
                } else {
                    // المراحل الإقصائية كصفحة واحدة
                    $fixtures = $stage->rounds
                        ->flatMap(fn($round) => $round->fixtures)
                        ->sortByDesc('starting_at')
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
                $fixtures = $stage->fixtures
                    ->sortByDesc(fn($fixture) => $fixture->starting_at?->timestamp ?? 0)
                    ->values();

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

        return view('frontEnd.football.rounds', compact(
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

    // اختياري: تحديث يدوي (إذا حط refresh_standings=1)
    private function refreshStandingsCache(int $seasonId, string $locale): void
    {
        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";

        $hasStandings = Standing::where('season_id', $seasonId)->exists();

        Cache::forget($cacheKey);
        Cache::forget($metaKey);

        if ($hasStandings) {
            app(SportmonksStandingService::class)->syncSeasonStandings($seasonId, $locale);
        }
    }

    private function getStandingsCached(int $seasonId, string $locale, bool $forceRefresh = false): array
    {
        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
            Cache::forget($metaKey);
        }

        $standings = Cache::remember($cacheKey, 3600, function () use ($seasonId, $metaKey) {
            $rows = Standing::with('participant')
                ->where('season_id', $seasonId)
                ->orderByRaw("CASE WHEN group_name IS NULL OR group_name = '' THEN 1 ELSE 0 END")
                ->orderBy('group_name')
                ->orderBy('position')
                ->get();

            $payload = $rows
                ->map(function (Standing $row) {
                    $item = is_array($row->payload_json) ? $row->payload_json : [];

                    // fallback لو payload_json فارغ أو ناقص
                    if (empty($item)) {
                        $item = [
                            'id'              => $row->sportmonks_standing_id,
                            'league_id'       => $row->league_id,
                            'season_id'       => $row->season_id,
                            'stage_id'        => $row->stage_id,
                            'round_id'        => $row->round_id,
                            'participant_id'  => $row->participant_id,
                            'participant'  => $row->participant,
                            'group_name'      => $row->group_name,
                            'position'        => $row->position,
                            'points'          => $row->points,
                            'played'          => $row->played,
                            'won'             => $row->won,
                            'draw'            => $row->draw,
                            'lost'            => $row->lost,
                            'goals_for'       => $row->goals_for,
                            'goals_against'   => $row->goals_against,
                            'goal_difference' => $row->goal_difference,
                            'form'            => [],
                            'details'         => [],
                        ];
                    }

                    // احقن participant من العلاقة إذا غير موجود داخل payload
                    if (!isset($item['participant']) || !is_array($item['participant'])) {
                        $item['participant'] = $row->participant ? $row->participant->toArray() : [];
                    }

                    // fallback للقيم الأساسية لو ناقصة داخل payload
                    $item['position']        = data_get($item, 'position', $row->position);
                    $item['points']          = data_get($item, 'points', $row->points);
                    $item['played']          = data_get($item, 'played', $row->played);
                    $item['won']             = data_get($item, 'won', $row->won);
                    $item['draw']            = data_get($item, 'draw', $row->draw);
                    $item['lost']            = data_get($item, 'lost', $row->lost);
                    $item['goals_for']       = data_get($item, 'goals_for', $row->goals_for);
                    $item['goals_against']   = data_get($item, 'goals_against', $row->goals_against);
                    $item['goal_difference'] = data_get($item, 'goal_difference', $row->goal_difference);
                    $item['group_name']      = data_get($item, 'group_name', $row->group_name);
                    $item['rule'] = data_get($item, 'rule', []);

                    $item['rule']['id'] = data_get($item, 'rule.id', $row->rule_id);
                    $item['rule']['name'] = data_get($item, 'rule.name', $row->rule_name);

                    $item['rule']['type'] = data_get($item, 'rule.type', []);
                    $item['rule']['type']['id'] = data_get($item, 'rule.type.id', $row->rule_type_id);
                    $item['rule']['type']['code'] = data_get($item, 'rule.type.code', $row->rule_type_code);
                    $item['rule']['type']['name'] = data_get($item, 'rule.type.name', $row->rule_type_name);

                    // لو form مخزنة كنص W,D,L نحولها لمصفوفة بسيطة للواجهة
                    if ((!isset($item['form']) || !is_array($item['form'])) && !empty($row->form)) {
                        $item['form'] = collect(explode(',', $row->form))
                            ->filter()
                            ->values()
                            ->map(fn($f, $i) => [
                                'form' => strtoupper(trim($f)),
                                'sort_order' => $i + 1,
                            ])
                            ->all();
                    }

                    return $item;
                })
                ->filter(fn($row) => is_array($row) && !empty($row))
                ->values()
                ->all();

            $lastSyncedAt = optional(
                $rows->sortByDesc('synced_at')->first()
            )->synced_at;

            Cache::put($metaKey, [
                'fetched_at' => $lastSyncedAt
                    ? $lastSyncedAt->toDateTimeString()
                    : now()->toDateTimeString(),
            ], 3600);

            return $payload;
        });

        $meta = Cache::get($metaKey, []);

        return [
            'ok'         => true,
            'error'      => null,
            'standings'  => $standings,
            'fetched_at' => data_get($meta, 'fetched_at'),
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
