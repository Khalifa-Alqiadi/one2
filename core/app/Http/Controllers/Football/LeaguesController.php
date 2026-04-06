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
        $leagues = \App\Models\League::with('country')->get();
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
        $name_var = 'name_' . Helper::currentLanguage()->code;
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

        if (!$hasStandings) {
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
