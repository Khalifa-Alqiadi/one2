<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use App\Helpers\Helper;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LeaguesController extends Controller
{
    private $uploadPath = "leagues";
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->middleware('auth');
    }

    public function index()
    {
        // $data_sections_arr = explode(",", Auth::user()->permissionsGroup->data_sections);
        // if (!$data_sections_arr) {
        //     return redirect()->route("NoPermission");
        // }

        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        if (@Auth::user()->permissionsGroup->view_status) {
            $Leagues = League::where('created_by', '=', Auth::user()->id);
        } else {
            $Leagues = League::query();
        }
        $search_word = request()->input("q");
        if ($search_word != "") {
            $Leagues = $Leagues->where('name', 'like', '%' . $search_word . '%');
        }

        $Leagues = $Leagues->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));

        return view('dashboard.leagues.list', compact('Leagues', 'GeneralWebmasterSections'));
    }

    public function edit($id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $League = League::find($id);
        $countries = Helper::countriesList();
        $tab = request()->input("tab", "details");
        if (!empty($League)) {
            return view('dashboard.leagues.edit', compact('League', 'GeneralWebmasterSections', 'countries', 'tab'));
        } else {
            return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        }
    }

    public function update(Request $request, $id)
    {
        $League = League::find($id);
        if (!empty($League)) {
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $League->{"name_" . $ActiveLanguage->code} = strip_tags($request->{"name_" . $ActiveLanguage->code});
                }
            }
            $League->country_id = $request->country_id;
            $League->status = $request->status;
            $League->updated_by = Auth::user()->id;
            $League->save();

            return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        } else {
            return redirect()->action([LeaguesController::class, 'index'])->with('errorMessage', __('backend.error'));
        }
    }

    public function updateAll(Request $request)
    {
        //
        if ($request->action == "order") {
            foreach ($request->row_ids as $rowId) {
                $League = League::find($rowId);
                if (!empty($League)) {
                    $row_no_val = "row_no_" . $rowId;
                    $League->row_no = $request->$row_no_val;
                    $League->save();
                }
            }
        } else {
            if ($request->ids != "") {
                if ($request->action == "activate") {
                    League::wherein('id', $request->ids)
                        ->update(['status' => 1]);
                } elseif ($request->action == "block") {
                    League::wherein('id', $request->ids)
                        ->update(['status' => 0]);
                }
            }
        }
        return redirect()->action([LeaguesController::class, 'index'])->with(
            'doneMessage',
            __('backend.saveDone')
        );
    }



    public function rounds(Request $request, $id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '1')
            ->orderBy('row_no', 'asc')
            ->get();

        $League = League::find($id);

        if (!$League) {
            return redirect()
                ->action([LeaguesController::class, 'index'])
                ->with('errorMessage', __('backend.error'));
        }

        $Seasons = $League->seasons()
            ->orderBy('starting_at', 'desc')
            ->get();

        $tab = $request->input('tab', 'rounds');

        // الموسم الحالي أو المحدد
        $seasonId = (int) $request->input('season_id', 0);
        if ($seasonId <= 0) {
            $seasonId = (int) (
                $League->current_season_id
                ?: $League->seasons()->where('is_current', 1)->value('id')
                ?: $League->seasons()->orderByDesc('starting_at')->value('id')
            );
        }

        /*
    |--------------------------------------------------------------------------
    | 1) جلب stages + rounds + fixtures
    |--------------------------------------------------------------------------
    */
        $stages = $League->stages()
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

        /*
    |--------------------------------------------------------------------------
    | 2) بناء قائمة صفحات العرض
    |    - جولات league phase أولاً (1 → 8)
    |    - ثم بقية المراحل
    |--------------------------------------------------------------------------
    */
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
            }else{
                $fixtures = Fixture::where('stage_id', $stage->id)
                    ->orderBy('starting_at', 'asc')
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

        /*
    |--------------------------------------------------------------------------
    | 3) pagination يدوي
    |--------------------------------------------------------------------------
    */
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

        /*
    |--------------------------------------------------------------------------
    | 4) انتقال تلقائي للجولة الحالية
    |--------------------------------------------------------------------------
    */
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

            return redirect()->route('leaguesRounds', [
                'id' => $id,
                'season_id' => $seasonId,
                'page' => $targetPage,
            ]);
        }

        return view('dashboard.leagues.rounds', compact(
            'League',
            'GeneralWebmasterSections',
            'tab',
            'Seasons',
            'seasonId',
            'stages',
            'paginatedPages'
        ));
    }




    public function roundsOld(Request $request, $id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $League = League::find($id);
        $Seasons = $League->seasons()->orderby('starting_at', 'desc')->get();
        $perPage = 1;

        // $rounds = $League->rounds()
        //     ->with('season', 'fixtures', 'fixtures.homeTeam', 'fixtures.awayTeam')
        //     ->orderby('starting_at', 'desc')
        //     ->paginate(1);

        $baseQuery = $League->rounds()->orderBy('starting_at', 'asc');

        // ✅ دور على الجولة الحالية
        $currentRound = (clone $baseQuery)->where('is_current', 1)->first();

        // ✅ إذا المستخدم ما مرر page، حوّله تلقائيًا لصفحة الجولة الحالية
        if (!$request->has('page') && $currentRound) {
            $countBefore = (clone $baseQuery)
                ->where('starting_at', '<', $currentRound->starting_at) // لأن desc
                ->count();

            $page = (int) floor($countBefore / $perPage) + 1;

            return redirect()->route('leaguesRounds', [
                'id' => $id,
                'page'     => $page,
            ]);
        }

        // ✅ pagination الطبيعي
        $rounds = $League->rounds()
            ->with('season', 'fixtures', 'fixtures.homeTeam', 'fixtures.awayTeam')
            ->whereHas('season', function ($q) {
                $q->where('is_current', 1);
            })
            ->orderBy('starting_at', 'asc') // عكس الترتيب عشان الجولة الحالية تكون في الأسفل
            ->paginate($perPage);

        $tab = request()->input("tab", "rounds");
        if (!empty($League)) {
            return view('dashboard.leagues.rounds', compact('League', 'GeneralWebmasterSections', 'tab', 'Seasons', 'rounds'));
        } else {
            return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        }
    }


    private function syncFixturesBySeasonAPI($league, int $seasonId, string $token, string $locale): int
    {
        $page = 1;
        $saved = 0;

        do {
            $url = "https://api.sportmonks.com/v3/football/fixtures"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&filters=fixtureSeasons:{$seasonId}"
                . "&include=participants;state;scores"
                . "&page={$page}";

            $res = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);

            $fixtures = collect(data_get($json, 'data', []))
                ->filter(fn($fx) => (int) data_get($fx, 'league_id', 0) === (int) $league->id)
                ->values();

            foreach ($fixtures as $match) {
                // participants
                $participants = collect(data_get($match, 'participants', []));
                $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
                $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

                $homeId = data_get($home, 'id') ?: data_get($home, 'participant_id');
                $awayId = data_get($away, 'id') ?: data_get($away, 'participant_id');

                // state
                $stateId   = (int) (data_get($match, 'state_id') ?: data_get($match, 'state.id'));
                $stateName = (string) data_get($match, 'state.name', '');
                $rawCode   = data_get($match, 'state.short_code')
                    ?? data_get($match, 'state.code')
                    ?? data_get($match, 'state.developer_name')
                    ?? null;

                $stateCode = $this->normalizeStateCode($rawCode, $stateName);

                $resultInfo = (string) data_get($match, 'result_info', '');
                $isFinished = $this->isFinishedState($stateCode, $stateName, $resultInfo);
                $isLive     = $this->isLiveState($stateCode, $stateName);

                // score
                [$homeScore, $awayScore] = $this->extractGoalsFromScores(data_get($match, 'scores', []) ?? []);

                $minute = data_get($match, 'time.minute');
                $minute = is_numeric($minute) ? (int) $minute : null;

                // ✅ إذا round موجودة، حدّثها/أنشئها
                // if (data_get($match, 'round_id')) {
                //     \App\Models\Round::updateOrCreate(
                //         ['id' => (int) data_get($match, 'round_id')],
                //         [
                //             'league_id'   => (int) $league->id,
                //             'season_id'   => (int) data_get($match, 'season_id'),
                //             'stage_id'    => data_get($match, 'stage_id') ? (int) data_get($match, 'stage_id') : null,
                //             'name'        => data_get($match, 'round.name'),
                //             'starting_at' => data_get($match, 'starting_at'),
                //             'ending_at'   => data_get($match, 'starting_at'),
                //         ]
                //     );
                // }

                \App\Models\Fixture::updateOrCreate(
                    ['id' => (int) data_get($match, 'id')],
                    [
                        'league_id'     => (int) data_get($match, 'league_id'),
                        'season_id'     => (int) data_get($match, 'season_id'),
                        'round_id'      => data_get($match, 'round_id') ? (int) data_get($match, 'round_id') : null,
                        'stage_id'      => data_get($match, 'stage_id') ? (int) data_get($match, 'stage_id') : null,

                        'home_team_id'  => $homeId ? (int) $homeId : null,
                        'away_team_id'  => $awayId ? (int) $awayId : null,

                        'starting_at'   => data_get($match, 'starting_at'),
                        'state_id'      => $stateId ?: null,
                        'state_name'    => $stateName ?: null,
                        'state_code'    => $stateCode ?: null,

                        'home_score'    => is_numeric($homeScore) ? (int) $homeScore : null,
                        'away_score'    => is_numeric($awayScore) ? (int) $awayScore : null,

                        'is_finished'   => $isFinished ? 1 : 0,
                        'ft_home_score' => $isFinished ? $homeScore : null,
                        'ft_away_score' => $isFinished ? $awayScore : null,
                        'minute'        => $isLive ? $minute : null,
                    ]
                );

                $saved++;
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return $saved;
    }


    public function update_api()
    {
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
                    'country' => $league['country_id'] ?? null,
                    'sport_id' => $league['sport_id'] ?? null,
                    'founded_year_estimated' => $foundedYear, // “تقديري” من أقدم موسم
                ];
            })
            ->values()
            ->all();
        if ($leagues) {
            foreach ($leagues as $league) {
                League::updateOrCreate(
                    ['id' => $league['id']], // شرط التطابق
                    [
                        'name_ar' => $league['name'], // يمكنك تعديل هذا إذا كان لديك اسم عربي
                        'name_en' => $league['name'], // يمكنك تعديل هذا إذا كان لديك اسم إنجليزي مختلف
                        'image_path' => $league['league_image'],
                        'country_id' => $league['country'],
                        'sport_id' => $league['sport_id'],
                    ]
                );
            }
        }
        return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
    }

    public function syncSeasonStructure(Request $request, $id)
    {
        $league = League::find($id);
        $seasonId = (int) $request->input('season_id');

        if (!$league || $seasonId <= 0) {
            return redirect()
                ->action([LeaguesController::class, 'index'])
                ->with('errorMessage', __('backend.error'));
        }

        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        // 1) stages
        $this->syncStagesAPI($league, $seasonId, $token, $locale);

        // 2) rounds التقليدية (مرحلة الدوري)
        $this->syncRoundsBySeasonAPI($league, $seasonId, $token, $locale);

        // 3) fixtures الموسم كله (يشمل playoff + R16 + QF + SF + Final)
        $savedFixtures = $this->syncFixturesBySeasonAPI($league, $seasonId, $token, $locale);

        return redirect()
            ->action([LeaguesController::class, 'rounds'], ['id' => $league->id])
            ->with('doneMessage', __('backend.saveDone') . " - {$savedFixtures} fixtures synced");
    }

    private function syncStagesAPI($league, int $seasonId, string $token, string $locale): void
    {
        $page = 1;

        do {
            $url = "https://api.sportmonks.com/v3/football/stages/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&page={$page}";

            $res = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);
            $items = collect(data_get($json, 'data', []))
                ->filter(fn($s) => (int) data_get($s, 'league_id', 0) === (int) $league->id)
                ->values();


            foreach ($items as $stage) {
                \App\Models\Stage::updateOrCreate(
                    ['id' => (int) data_get($stage, 'id')],
                    [
                        'league_id'   => (int) $league->id,
                        'season_id'   => (int) data_get($stage, 'season_id'),
                        'name_ar'     => data_get($stage, 'name'),
                        'name_en'     => data_get($stage, 'name'),
                        'type_id'     => data_get($stage, 'type_id'),
                        'type_name'   => data_get($stage, 'type.name', data_get($stage, 'type', null)),
                        'sort_order'  => data_get($stage, 'sort_order'),
                        'finished'    => (bool) data_get($stage, 'finished', false),
                        'is_current'  => (bool) data_get($stage, 'is_current', false),
                        'starting_at' => data_get($stage, 'starting_at'),
                        'ending_at'   => data_get($stage, 'ending_at'),
                    ]
                );
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }

    private function syncRoundsBySeasonAPI($league, int $seasonId, string $token, string $locale): void
    {
        $page = 1;

        do {
            $url = "https://api.sportmonks.com/v3/football/rounds/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&include=fixtures"
                . "&page={$page}";

            $res = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);
            $rounds = collect(data_get($json, 'data', []))
                ->filter(fn($r) => (int) data_get($r, 'league_id', 0) === (int) $league->id)
                ->unique('id')
                ->values();

            foreach ($rounds as $round) {
                $this->matchesAPI(data_get($round, 'fixtures', []), $token, $locale);

                \App\Models\Round::updateOrCreate(
                    ['id' => (int) data_get($round, 'id')],
                    [
                        'league_id'              => (int) $league->id,
                        'season_id'              => data_get($round, 'season_id'),
                        'stage_id'               => data_get($round, 'stage_id'),
                        'name'                   => data_get($round, 'name'),
                        'finished'               => (bool) data_get($round, 'finished', false),
                        'is_current'             => (bool) data_get($round, 'is_current', false),
                        'games_in_current_week'  => (bool) data_get($round, 'games_in_current_week', false),
                        'starting_at'            => data_get($round, 'starting_at'),
                        'ending_at'              => data_get($round, 'ending_at'),
                    ]
                );
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }



    public function roundsAPI(Request $request, $id)
    {
        $league = League::find($id);
        $seasonId = $request->input('season_id');
        if (!$league) {
            return redirect()
                ->action([LeaguesController::class, 'index'])
                ->with('errorMessage', __('backend.error'));
        }

        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code;

        $page  = 1;
        $saved = 0;

        do {
            $url = "https://api.sportmonks.com/v3/football/rounds/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&include=fixtures"
                . "&page={$page}";

            $roundsRes = $this->apiClient->curlGet($url);

            $json   = data_get($roundsRes, 'json', []);
            $rounds = $json['data'] ?? [];

            $rounds = collect($rounds)

                ->filter(fn($fx) => (int) data_get($fx, 'league_id', 0) === (int) $league->id)
                ->unique('id')
                ->values()
                ->all();

            foreach ($rounds as $round) {
                $this->matchesAPI(data_get($round, 'fixtures', []), $token, $locale);
                \App\Models\Round::updateOrCreate(
                    ['id' => $round['id']],
                    [
                        'league_id'              => $league->id,
                        'season_id'              => $round['season_id'] ?? null,
                        'stage_id'               => $round['stage_id'] ?? null,
                        'name'                   => $round['name'] ?? null,
                        'finished'               => (bool)($round['finished'] ?? false),
                        'is_current'             => (bool)($round['is_current'] ?? false),
                        'games_in_current_week'  => (bool)($round['games_in_current_week'] ?? false),
                        'starting_at'            => $round['starting_at'] ?? null,
                        'ending_at'              => $round['ending_at'] ?? null,
                    ]
                );

                $saved++;
            }

            // ✅ pagination الصحيح من الجذر
            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return redirect()
            ->action([LeaguesController::class, 'rounds'], ['id' => $league->id])
            ->with('doneMessage', __('backend.saveDone') . " - {$saved} rounds updated");
    }

    public function matchesAPI($fixtures, $token, $locale)
    {
        foreach ($fixtures as $fx) {
            $fixtureId = (int) data_get($fx, 'id', 0);
            if ($fixtureId <= 0) continue;

            $fixtureUrl = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participants;state;scores;periods;events";

            $fxRes = $this->apiClient->curlGet($fixtureUrl);
            if (!data_get($fxRes, 'ok')) continue;

            $match = data_get($fxRes, 'json.data', []);
            if (!$match) continue;

            // participants home/away
            $participants = collect(data_get($match, 'participants', []));
            $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
            $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

            $homeId = data_get($home, 'id') ?: data_get($home, 'participant_id');
            $awayId = data_get($away, 'id') ?: data_get($away, 'participant_id');

            // state
            $stateId   = (int) (data_get($match, 'state_id') ?: data_get($match, 'state.id'));
            $stateName = (string) (data_get($match, 'state.name') ?: data_get($match, 'state_name') ?: '');

            // ✅ state_code extraction (robust)
            $rawCode = data_get($match, 'state.short_code')
                ?? data_get($match, 'state.code')
                ?? data_get($match, 'state.developer_name')
                ?? data_get($match, 'state.state')
                ?? null;

            $stateCode = $this->normalizeStateCode($rawCode, $stateName);

            // ✅ finished/live flags تعتمد على code
            $isFinished = $this->isFinishedState($stateCode, $stateName, (string)data_get($match, 'result_info', ''));
            // $isLive     = $this->isLiveState($stateCode, $stateName);

            // scores
            $scoresArr = data_get($match, 'scores', []) ?? [];
            [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);
            // ft scores
            $ftHome = $isFinished && is_numeric($homeScore) ? (int)$homeScore : null;
            $ftAway = $isFinished && is_numeric($awayScore) ? (int)$awayScore : null;

            // minute (live)
            $minute = data_get($match, 'periods.minute');
            $minute = is_numeric($minute) ? (int)$minute : null;
            \App\Models\Fixture::updateOrCreate(
                ['id' =>  $match['id']],
                [
                    'league_id'     => (int) data_get($match, 'league_id'),
                    'season_id'     => (int) data_get($match, 'season_id'),
                    'round_id'      => data_get($match, 'round_id') ? (int) data_get($match, 'round_id') : null,
                    'stage_id'      => data_get($match, 'stage_id') ? (int) data_get($match, 'stage_id') : null,

                    'home_team_id'  => $homeId ? (int)$homeId : null,
                    'away_team_id'  => $awayId ? (int)$awayId : null,

                    'starting_at'   => data_get($match, 'starting_at'),

                    'state_id'      => $stateId ?: null,
                    'state_name'    => $stateName ?: null,
                    'state_code'    => $stateCode ?: null, // ✅ صار يتعبّى

                    'home_score'    => is_numeric($homeScore) ? (int)$homeScore : null,
                    'away_score'    => is_numeric($awayScore) ? (int)$awayScore : null,

                    'is_finished'   => $isFinished ? 1 : 0,
                    'ft_home_score' => $ftHome,
                    'ft_away_score' => $ftAway,

                    // ✅ الدقيقة تخزن فقط لو Live (اختياري)
                    'minute'        => $minute !== null ? (int)$minute : null,
                ]
            );
        }
    }

    private function normalizeStateCode($rawCode, string $stateName): ?string
    {
        $code = strtoupper(trim((string) $rawCode));
        $name = mb_strtolower(trim($stateName));

        // لو الـ API ما رجّع code
        if ($code === '' || $code === 'NULL') {
            // استنتاج من الاسم العربي/الانجليزي
            if (str_contains($name, 'نهاية') || str_contains($name, 'انته') || str_contains($name, 'نهائ') || str_contains($name, 'finished') || str_contains($name, 'full time')) {
                return 'FT';
            }
            if (str_contains($name, 'مباشر') || str_contains($name, 'live') || str_contains($name, 'in play') || str_contains($name, 'inplay')) {
                return 'LIVE';
            }
            if (str_contains($name, 'الشوط') || str_contains($name, 'half')) {
                return 'HT';
            }
            if (str_contains($name, 'لم تبدأ') || str_contains($name, 'not started') || str_contains($name, 'ns')) {
                return 'NS';
            }
            return null;
        }

        // توحيد أكواد محتملة من SportMonks
        $map = [
            'FT' => 'FT',
            'FINISHED' => 'FT',
            'FULLTIME' => 'FT',
            'FULL_TIME' => 'FT',

            'LIVE' => 'LIVE',
            'INPLAY' => 'LIVE',
            'IN_PLAY' => 'LIVE',
            'PLAYING' => 'LIVE',

            'HT' => 'HT',
            'HALFTIME' => 'HT',
            'HALF_TIME' => 'HT',

            'NS' => 'NS',
            'NOTSTARTED' => 'NS',
            'NOT_STARTED' => 'NS',

            'ET' => 'ET',
            'AET' => 'ET',
            'EXTRATIME' => 'ET',
            'EXTRA_TIME' => 'ET',

            'PEN' => 'PEN',
            'PENALTIES' => 'PEN',

            'POSTP' => 'POSTP',
            'POSTPONED' => 'POSTP',

            'CANC' => 'CANC',
            'CANCELLED' => 'CANC',
            'CANCELED' => 'CANC',

            'SUSP' => 'SUSP',
            'SUSPENDED' => 'SUSP',

            'ABD' => 'ABD',
            'ABANDONED' => 'ABD',
        ];

        $code = str_replace([' ', '-'], '_', $code);
        $code = $map[$code] ?? $code;

        // تقصير أكواد طويلة
        if (strlen($code) > 10) {
            $code = substr($code, 0, 10);
        }

        return $code ?: null;
    }

    private function isLiveState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        if (in_array($code, ['LIVE', 'HT', 'ET', 'PEN'], true)) return true;

        return str_contains($name, 'مباشر') || str_contains($name, 'live') || str_contains($name, 'in play') || str_contains($name, 'inplay');
    }

    private function isFinishedState(?string $stateCode, string $stateName, string $resultInfo): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);
        $resultInfo = trim($resultInfo);

        // codes نهائية
        if (in_array($code, ['FT', 'CANC', 'ABD', 'SUSP'], true)) return true;

        // نصوص تدل على نهاية
        $isFinishedByText =
            str_contains($name, 'finished') ||
            str_contains($name, 'full') ||
            str_contains($name, 'ended') ||
            str_contains($name, 'final') ||
            str_contains($name, 'انته') ||
            str_contains($name, 'نهائ') ||
            str_contains($name, 'نهاية') ||
            str_contains($name, 'مكتمل');

        // لو result_info موجود غالبًا انتهت
        $isFinishedByResult = $resultInfo !== '';

        return $isFinishedByText || $isFinishedByResult;
    }

    private function extractGoalsFromScores($scores): array
    {
        $scores = is_array($scores) ? $scores : [];
        $col = collect($scores)->sort(); // ترتيب تنازلي حسب الأهداف عشان نجيب أعلى نتيجة أولًا

        // 1) فلترة: نجيب فقط نوع "goals" إذا موجود
        $goalsOnly = $col->filter(function ($s) {
            $type =
                mb_strtolower((string) data_get($s, 'type.code', data_get($s, 'type.name', data_get($s, 'type', ''))));

            // إذا ما عنده type اعتبره محتمل
            if ($type === '') return true;

            // أشهر تسميات
            return str_contains($type, 'goal');
        });

        $base = $goalsOnly->isNotEmpty() ? $goalsOnly : $col;

        // 2) استبعاد السجلات الملغية إن كان API يعطيها (حسب حسابك قد تكون موجودة أو لا)
        $base = $base->reject(function ($s) {
            $label = mb_strtolower((string) data_get($s, 'description', data_get($s, 'score.description', '')));
            $status = mb_strtolower((string) data_get($s, 'status', data_get($s, 'score.status', '')));
            $confirmed = data_get($s, 'confirmed', data_get($s, 'score.confirmed'));

            // كلمات شائعة للملغي/غير محتسب
            if (str_contains($label, 'offside') || str_contains($label, 'تسلل')) return true;
            if (str_contains($label, 'disallow') || str_contains($label, 'cancel')) return true;

            // إذا confirmed موجود وصار false
            if ($confirmed === false || $confirmed === 0) return true;

            // status إذا كان invalid / cancelled
            if (str_contains($status, 'invalid') || str_contains($status, 'cancel')) return true;

            return false;
        });

        // 3) نرتّب حسب "priority" (CURRENT ثم LIVE ثم FT) إن وجد
        $priority = function ($s) {
            $desc = mb_strtolower((string) data_get($s, 'description', data_get($s, 'score.description', '')));
            $name = mb_strtolower((string) data_get($s, 'name', ''));
            $code = mb_strtolower((string) data_get($s, 'score.name', ''));

            $hay = $desc . ' ' . $name . ' ' . $code;

            if (str_contains($hay, 'current')) return 0;
            if (str_contains($hay, 'live')) return 1;
            if (str_contains($hay, 'ft') || str_contains($hay, 'full')) return 2;

            // آخر خيار
            return 9;
        };

        $base = $base->sortBy($priority)->values();

        // 4) استخرج home/away goals من أفضل مجموعة
        $homeRow = $base->first(fn($s) => data_get($s, 'score.participant') === 'home');
        $awayRow = $base->first(fn($s) => data_get($s, 'score.participant') === 'away');

        $homeGoals = data_get($homeRow, 'score.goals');
        $awayGoals = data_get($awayRow, 'score.goals');

        $homeGoals = is_numeric($homeGoals) ? (int) $homeGoals : null;
        $awayGoals = is_numeric($awayGoals) ? (int) $awayGoals : null;
        return [$homeGoals, $awayGoals];
    }



    public function matchesAPIOLD($fixtures, $token, $locale)
    {
        foreach ($fixtures as $fx) {
            $fixtureId = (int) ($fx['id'] ?? 0);
            if ($fixtureId <= 0) continue;

            $fixtureUrl = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participants;state;scores"; // ✅ بدون events

            $fxRes = $this->apiClient->curlGet($fixtureUrl);
            if (!data_get($fxRes, 'ok')) continue;

            $match = data_get($fxRes, 'json.data', []);
            if (!$match) continue;

            // home/away teams
            $participants = collect(data_get($match, 'participants', []));
            $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
            $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

            $homeId = data_get($home, 'id') ?: data_get($home, 'participant_id');
            $awayId = data_get($away, 'id') ?: data_get($away, 'participant_id');

            // state + finished
            $stateId   = (int) (data_get($match, 'state_id') ?: data_get($match, 'state.id'));
            $stateName = (string) data_get($match, 'state.name', '');
            $st = mb_strtolower($stateName);

            $resultInfo = (string) data_get($match, 'result_info', '');

            // ✅ حالات نصية كثيرة
            $isFinishedByText =
                str_contains($st, 'finished') ||
                str_contains($st, 'ft') ||
                str_contains($st, 'full') ||
                str_contains($st, 'ended') ||
                str_contains($st, 'final') ||
                str_contains($st, 'انته') ||
                str_contains($st, 'نهائ') ||
                str_contains($st, 'نهاية') ||
                str_contains($st, 'مكتمل');

            // ✅ إذا result_info موجود غالبًا انتهت
            $isFinishedByResult = trim($resultInfo) !== '';

            // ✅ fallback: إذا الوصف CURRENT موجود بس state يدل إنها نهاية
            $isFinished = $isFinishedByText || $isFinishedByResult;


            // ✅ goals from scores فقط
            $scoresArr = data_get($match, 'scores', []) ?? [];
            [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);

            // FT فقط إذا انتهت
            $ftHome = $isFinished && is_numeric($homeScore) ? (int) $homeScore : null;
            $ftAway = $isFinished && is_numeric($awayScore) ? (int) $awayScore : null;

            // minute (live)
            $minute = data_get($match, 'time.minute');

            Fixture::updateOrCreate(
                ['id' => (int) data_get($match, 'id')],
                [
                    'league_id'     => (int) data_get($match, 'league_id'),
                    'season_id'     => (int) data_get($match, 'season_id'),
                    'round_id'      => data_get($match, 'round_id') ? (int) data_get($match, 'round_id') : null,

                    'home_team_id'  => $homeId ? (int) $homeId : null,
                    'away_team_id'  => $awayId ? (int) $awayId : null,

                    'starting_at'   => data_get($match, 'starting_at'),
                    'state_id'      => $stateId ?: null,
                    'state_name'    => $stateName ?: null,

                    // ✅ goals
                    'home_score'    => is_numeric($homeScore) ? (int) $homeScore : null,
                    'away_score'    => is_numeric($awayScore) ? (int) $awayScore : null,

                    'is_finished'   => $isFinished ? 1 : 0,
                    'ft_home_score' => $ftHome,
                    'ft_away_score' => $ftAway,

                    'minute'        => is_numeric($minute) ? (int) $minute : null,
                ]
            );
        }
    }




    private function extractGoalsFromScoresOld($scores): array
    {
        $scores = is_array($scores) ? $scores : [];
        $col = collect($scores);

        // ✅ شكل SportMonks عندك: score.goals + score.participant (home/away)
        $homeRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'home');
        $awayRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'away');

        $homeGoals = data_get($homeRow, 'score.goals');
        $awayGoals = data_get($awayRow, 'score.goals');

        // أحيانًا goals يرجع string
        $homeGoals = is_numeric($homeGoals) ? (int) $homeGoals : null;
        $awayGoals = is_numeric($awayGoals) ? (int) $awayGoals : null;

        return [$homeGoals, $awayGoals];
    }






    private function extractRoundIdsFromSchedule($scheduleData): array
    {
        // normalize إذا data جاية list
        if (is_array($scheduleData) && array_key_exists(0, $scheduleData)) {
            $scheduleData = $scheduleData[0] ?? [];
        }

        $rounds = data_get($scheduleData, 'rounds', []);
        $rounds = is_array($rounds) ? $rounds : [];

        return collect($rounds)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }







    /**
     * يحاول يقرأ أهداف home/away بشكل آمن من scores
     */
    private function parseHomeAwayGoals(array $match, int $homeId, int $awayId): array
    {
        $scores = collect(data_get($match, 'scores', []));

        $preferred = $scores->firstWhere('description', 'CURRENT')
            ?? $scores->firstWhere('description', 'FINAL')
            ?? null;

        // بعض الأحيان scores تكون multiple lines، فنجمع لكل فريق
        $homeGoals = null;
        $awayGoals = null;

        foreach ($scores as $row) {
            $pid = (int) (data_get($row, 'score.participant_id') ?? data_get($row, 'participant_id') ?? 0);
            $goals = data_get($row, 'score.goals');

            if ($pid && $goals !== null) {
                if ($pid === $homeId) $homeGoals = $goals;
                if ($pid === $awayId) $awayGoals = $goals;
            }
        }

        // لو ما قدرنا نحدد بالـ participant_id، نرجع nulls
        return [$homeGoals, $awayGoals];
    }
}
