<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Round;
use App\Services\ApiClientService;
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
            }else{
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
        Cache::forget($cacheKey);
        Cache::forget($metaKey);
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
