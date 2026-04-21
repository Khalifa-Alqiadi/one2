<?php

namespace App\Http\Controllers\Dashboard\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SyncSportmonksTeamsJob;
use App\Models\Team;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->middleware('auth');
    }
    public function index($country_id = 0)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $countries = \App\Models\Country::where('status', 1)->orderBy('sport_id')->get();
        return view('dashboard.football.teams.list', compact('GeneralWebmasterSections', 'countries', 'country_id'));
    }

    public function list(Request $request)
    {
        $limit = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $locale = Helper::currentLanguage()->code;
        $name = "name_" . $locale;
        $title = "title_" . $locale;

        $dir = $request->input('order.0.dir', 'desc');
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $q = $request->input('find_q');
        $country_id = $request->input('country_id');

        // أعمدة datatable حسب ترتيبها في JS
        $columns = [
            'id',
            "$name",
            'short_code',
            'country_id',
            'venue_id',
            'founded',
            'status',
            'updated_at',
        ];

        $order = $columns[$orderColumnIndex] ?? 'id';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'desc';

        // Query
        $query = \App\Models\Team::query();

        // فلترة بسيطة اختيارية (لو تبغى)
        // $q = trim((string) $request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name_ar', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%");
            });
        }

        if ($country_id > 0) {
            $query->where('country_id', $country_id);
        }

        // status optional
        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status'));
        }

        $totalData = \App\Models\Team::count();
        $totalFiltered = (clone $query)->count();

        // paginate + order
        $rows = $query->orderBy($order, $dir)
            ->orderBy('id', 'desc')
            ->offset($start)
            ->limit($limit > 0 ? $limit : 10)
            ->get();

        $data = [];
        $x = 0;
        $teamsCount = $rows->count();
        foreach ($rows as $team) {
            $x++;
            $logo = $team->image_path
                ? " <div class=\"pull-right\"><img src=\"" . $team->image_path . "?w=150&h=150\" style=\"height: 40px\" alt=\"" . $title . "\"></div>"
                : '-';
            // $logo = $team->image_path
            //     ? '<img src="' . $team->image_path . '" style="width:28px;height:28px;object-fit:contain;border-radius:6px;background:#fff;padding:2px" />'
            //     : '-';
            $statusHtml = "<i class=\"fa " . (($team->status == 1)
                ? "fa-check text-success"
                : "fa-times text-danger") . " inline\"></i>";

            $options = '
                      <div class="dropdown ' . ((($x + 2) >= $teamsCount) ? "dropup" : "") . '">
                    <button type="button" class="btn btn-sm light dk dropdown-toggle" data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i> ' . __('backend.options') . '</button>
                    <div class="dropdown-menu pull-right">';
            if (@Auth::user()->permissionsGroup->edit_status) {
                $options .= '<a class="dropdown-item" href="' . route("teams.edit", [
                    "id" => $team->id
                ]) . '"><i class="material-icons">&#xe3c9;</i> ' . __('backend.edit') . '</a>';
                $options .= '<a class="dropdown-item" href="' . route("update.players", [
                    "id" => $team->id
                ]) . '"><i class="material-icons">&#xe3c9;</i> ' . __('backend.update_players') . '</a>';
            }
            if (@Auth::user()->permissionsGroup->delete_status) {
                $options .= '<a class="dropdown-item text-danger" onclick="DeleteTeam(\'' . $team->id . '\')"><i class="material-icons">&#xe872;</i> ' . __('backend.delete') . '</a>';
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
                'name'       => '<div>' . $team->name_ar . '</div>',
                'name'       => "<a href='" . route("teams.edit", [
                    "id" => $team->id
                ]) . "'>" . $logo . "<div class='h6 m-b-0'>" . $team->$name . "</div></a>",
                'country_id' => '<div class="text-center">' . ($team->country->$title ?? '-') . '</div>',
                // 'venue_id'   => '<div class="text-center">' . ($team->venue_id ?? '-') . '</div>',
                // 'founded'    => '<div class="text-center">' . ($team->founded ?? '-') . '</div>',
                'status'     => '<div class="text-center">' . $statusHtml . '</div>',
                'updated_at' => '<div class="text-center">' . $team->updated_at?->format('Y-m-d H:i') . '</div>',
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

    public function edit($id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $countries = \App\Models\Country::where('status', 1)->orderBy('sport_id')->get();
        $team = Team::findOrFail($id);
        return view('dashboard.football.teams.edit', compact('GeneralWebmasterSections', 'countries', 'team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $data = $request->validate([
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'status'     => 'required|boolean',
            'major_competitions' => 'required|boolean',
            'major_national_teams' => 'required|boolean',
        ]);

        $team->update($data);

        return redirect()->action(
                    [TeamsController::class, 'edit'],
                    ['id' => $id]
                )->with(
                    'doneMessage',
                    __('backend.saveDone')
                );
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return json_encode(array("stat" => "success", "id" => $id));
    }

    public function updateAll(Request $request)
    {
        $ids = $request->input('ids', []);
        $rowNos = $request->input('row_no', []);

        if (is_array($ids) && count($ids) > 0) {
            if ($request->input('action') === 'delete') {
                 Team::whereIn('id', $ids)->delete();
            } elseif ($request->input('action') === 'activate') {
                Team::whereIn('id', $ids)->update(['status' => 1]);
            } elseif ($request->input('action') === 'block') {
                Team::whereIn('id', $ids)->update(['status' => 0]);
             }
        }

        return redirect()->action([TeamsController::class, 'index'])->with('doneMessage', __('backend.saveDone'));

    }


    public function updateAPI()
    {
        $locale = Helper::currentLanguage()->code;
        $token  = config('services.SPORTMONKS_TOKEN');

        // شغّلها في الخلفية
        SyncSportmonksTeamsJob::dispatch($token, $locale)
            ->onQueue('sportmonks');

        return redirect()
            ->action([TeamsController::class, 'index'])
            ->with('doneMessage', __('backend.saveDone') . ' (Sync started in background)');
    }
    public function updatePlayers($id)
    {
        $locale = Helper::currentLanguage()->code;
        $token  = config('services.SPORTMONKS_TOKEN');

        $seasonId = Helper::currentSeason();
        $service = app(\App\Services\PlayerSyncService::class);
        $result = $service->syncByTeam(teamId: $id, seasonId: $seasonId);

        return redirect()
            ->action([TeamsController::class, 'index'])
            ->with('doneMessage', __('backend.saveDone') . ' (Sync started in background)');
    }
}
