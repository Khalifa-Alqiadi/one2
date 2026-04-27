<?php

namespace App\Http\Controllers\Dashboard\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use App\Services\UpdatesLeaguesAndSeasonsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $Leagues = $Leagues->orderby('row_no', 'desc')->paginate(config('smartend.backend_pagination'));

        return view('dashboard.football.leagues.list', compact('Leagues', 'GeneralWebmasterSections'));
    }

    public function edit($id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $League = League::find($id);
        $countries = Helper::countriesList();
        $tab = request()->input("tab", "details");
        if (!empty($League)) {
            return view('dashboard.football.leagues.edit', compact('League', 'GeneralWebmasterSections', 'countries', 'tab'));
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
            $League->major_competitions = $request->major_competitions;
            // $League->updated_by = Auth::user()->id;
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

    public function update_api()
    {
        app(UpdatesLeaguesAndSeasonsServices::class)->loadLeagues();
        return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
    }
}
