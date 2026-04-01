<?php

namespace App\Http\Controllers\Dashboard\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\League;
use App\Models\Season;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RoundsController extends Controller
{
    private $uploadPath = "leagues";
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->middleware('auth');
    }
    public function indexOld(Request $request, $league_id, $season_id){
        $GeneralWebmasterSections = WebmasterSection::where('status', '1')
            ->orderBy('row_no', 'asc')
            ->get();

        $League = League::find($league_id);
        $Season = Season::find($season_id);

        if (!$League) {
            return redirect()
                ->action([LeaguesController::class, 'index'])
                ->with('errorMessage', __('backend.error'));
        }



        /*
        |--------------------------------------------------------------------------
        | 1) جلب stages + rounds + fixtures
        |--------------------------------------------------------------------------
        */
        $stages = $League->stages()
            ->with([
                'rounds' => function ($q) use ($Season) {
                    $q->where('season_id', $Season->id)
                        ->orderBy('starting_at', 'asc');
                },
                'fixtures' => function ($fx) {
                    $fx->orderBy('starting_at', 'asc');
                },
                'fixtures.homeTeam',
                'fixtures.awayTeam',
            ])
            ->where('season_id', $Season->id)
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
            }
            foreach ($pages as $index => $page) {

                if ($page['type'] === 'stage' && $page['stage'] && $page['stage']->is_current && $targetIndex <= 0) {
                    $targetIndex = $index;
                    break;
                }
            }

            $targetPage = $targetIndex + 1;

            return redirect()->route('leaguesRounds', [
                'league_id' => $League->id,
                'season_id' => $Season->id,
                'page' => $targetPage,
            ]);
        }

        $tab = $request->input('tab', 'matches');

        return view('dashboard.football.rounds.list', compact(
            'League',
            'GeneralWebmasterSections',
            'Season',
            'stages',
            'paginatedPages',
            'tab'
        ));
    }
    public function index(Request $request, $league_id){
        $GeneralWebmasterSections = WebmasterSection::where('status', '1')
            ->orderBy('row_no', 'asc')
            ->get();

        $League = League::find($league_id);

        $seasons = $League->seasons()->orderBy('starting_at', 'desc')->get();



        $tab = $request->input('tab', 'matches');

        return view('dashboard.football.rounds.list', compact(
            'League',
            'GeneralWebmasterSections',
            'tab',
            'seasons'
        ));
    }

    public function list(Request $request, $league_id)
    {
        $limit = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $locale = Helper::currentLanguage()->code;
        $name = "name_" . $locale;
        $title = "title_" . $locale;

        $dir = $request->input('order.0.dir', 'desc');
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $q = $request->input('find_q');
        $season_id = $request->input('season_id');
        $find_date = $request->input('date');
        $find_status = $request->input('status');

        if (!$season_id) {
            $season_id = Season::where('is_current', 1)
                ->where('league_id', $league_id)
                ->value('id');
        }
        $matchesQuery = Fixture::where('season_id', $season_id)
            ->where('league_id', $league_id)
            ->with(['homeTeam', 'awayTeam']);

        if($find_date){
            $matchesQuery = $matchesQuery->whereDate('starting_at', $find_date);
        }
        if($find_status){
            if ($find_status === 'not_started') {
                $matchesQuery = $matchesQuery->where(function($q) {
                    $q->where('is_finished', 0);
                });
            } elseif ($find_status === 'live') {
                $matchesQuery = $matchesQuery->where(function($q) {
                    $q->where('is_finished', 0)
                        ->where('starting_at', '<=', now());
                });
            } elseif ($find_status === 'finished') {
                $matchesQuery = $matchesQuery->where('is_finished', 1);
            }
        }

        // أعمدة datatable حسب ترتيبها في JS
        $columns = [
            'id',
            "title",
            'season_id',
            'starting_at',
            'is_finished',
        ];

        $order = $columns[$orderColumnIndex] ?? 'starting_at';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'desc';

        // فلترة بسيطة اختيارية (لو تبغى)
        // $q = trim((string) $request->input('q', ''));
        // if ($q !== '') {
        //     $matchesQuery->where(function ($qq) use ($q) {
        //         $qq->where('name_ar', 'like', "%{$q}%")
        //             ->orWhere('name_en', 'like', "%{$q}%");
        //     });
        // }





        $totalData = $matchesQuery->count();
        $totalFiltered = (clone $matchesQuery)->count();

        // paginate + order
        $rows = $matchesQuery->orderBy($order, $dir)
            ->orderBy('starting_at', 'asc')
            ->limit($limit > 0 ? $limit : 10)
            ->get();

        $data = [];
        $x = 0;
        $matchsCount = $rows->count();
        foreach ($rows as $team) {
            $x++;
            $logo = '<a href="' . route('matcheRoundsEdit', ['id' => $team->id]) . '"
                    class="d-flex justify-content-between"
                    style="justify-content: space-between; display:flex">
                    <div>';
            if($team->homeTeam || $team->awayTeam){
                if ($team->homeTeam->image_path){
                    $logo .= '<img src="' . $team->homeTeam->image_path . '"
                        style="height:30px; margin: 0 4px;" alt="">';
                }
                $logo .= '<span>' . $team->homeTeam->$name . '</span></div>';
                $logo .= '<span class="m-x-sm">vs</span>';
                $logo .= '<div>';
                if ($team->awayTeam) {
                    if ($team->awayTeam->image_path) {
                        $logo .= '<img src="' . $team->awayTeam->image_path . '"
                            style="height:30px; margin: 0 4px;" alt="">';
                    }
                    $logo .= '<span>' . $team->awayTeam->$name . '</span>';
                }
                $logo .= '</div>';
            }
            $logo .= '</a>';
            $starting_at = "<div class='text-center'>" . ($team->starting_at ? Carbon::parse($team->starting_at)->format('Y-m-d H:i') : '-') . "</div>";
            $is_finished = "<div class='text-center'>";
                if ($team->is_finished)
                    $is_finished .= '<span class="text-info">' . __('backend.finished') . '</span>';
                elseif ($team->starting_at && $team->starting_at > now())
                    $is_finished .= '<span class="text-success">' . __('backend.not_started_yet') . '</span>';
                else
                    $is_finished .= '<span class="text-warning">' . __('backend.live_now') . '</span>';
            $is_finished .= "</div>";

            $options = '
                      <div class="dropdown ' . ((($x + 2) >= $matchsCount) ? "dropup" : "") . '">
                    <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i> ' . __('backend.options') . '</button>
                    <div class="dropdown-menu pull-right">';
            if (@Auth::user()->permissionsGroup->edit_status) {
                $options .= '<a class="dropdown-item" href="' . route("matcheRoundsEdit", [
                    "id" => $team->id
                ]) . '"><i class="material-icons">&#xe3c9;</i> ' . __('backend.edit') . '</a>';
            }
            $options .= '</div></div>';

            $data[] = [
                'check' => "<div class='row_checker'><label class=\"ui-check m-a-0\">
                            <input type=\"checkbox\" name=\"ids[]\" value=\"" . $team->id . "\"><i
                                    class=\"dark-white\"></i>
                                    <input type='hidden' name='row_ids[]' value='" . $team->id . "' class='form-control row_no'>
                        </label>
                    </div>",
                'id'         => '<div class="text-center">' . $team->id . '</div>',
                // 'logo'       => '<div class="text-center">' . $logo . '</div>',
                'title'       => $logo,
                'starting_at' => $starting_at,
                'is_finished' => $is_finished,
                'options'    => "<div class='text-center'>" . $options . "</div>",
            ];
        }

        return response()->json([
            "draw"            => (int) $request->input('draw'),
            "recordsTotal"    => (int) $totalData,
            "recordsFiltered" => (int) $totalFiltered,
            "data"            => $data,
        ]);
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

    public function syncSeasonStructure(Request $request, $league_id, $season_id)
    {
        $league = League::find($league_id);
        $season = Season::find($season_id);
        $seasonId = (int) $season_id;

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
            ->action([SeasonsController::class, 'index'], ['league_id' => $league->id])
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

    public function matcheRoundsEdit($id){
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $match = Fixture::with([
            'league',
            'season',
            'homeTeam',
            'awayTeam'
        ])->find($id);

        $today = Carbon::now()->addMinutes(110);


        if (!empty($match)) {
            return view('dashboard.football.rounds.details', compact('match', 'GeneralWebmasterSections', 'today'));
        } else {
            return redirect()->action([RoundsController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        }
    }

    public function matchUpdate(Request $request, $id){
        $fixture = Fixture::findOrFail($id);

        $fixture->update([
            'is_home'   => $request->boolean('is_home'),
            'is_slider' => $request->boolean('is_slider'),
        ]);

        return redirect()->action([RoundsController::class, 'matcheRoundsEdit'], ['id' => $id])->with('doneMessage', __('backend.saveDone'));
    }
}
