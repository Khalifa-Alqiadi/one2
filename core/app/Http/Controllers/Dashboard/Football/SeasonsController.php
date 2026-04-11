<?php

namespace App\Http\Controllers\Dashboard\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Season;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use App\Services\UpdatesLeaguesAndSeasonsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeasonsController extends Controller
{
     private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->middleware('auth');
    }

    public function index(Request $request, League $League_id)
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $League = $League_id;
        $Season = $League->seasons()->where('is_current', 1)->first();
        if (@Auth::user()->permissionsGroup->view_status) {
            $Seasons = Season::where('created_by', '=', Auth::user()->id)
                ->where('league_id', $League->id);
        } else {
            $Seasons = Season::where('league_id', $League->id);
        }
        $search_word = request()->input("q");
        $tab = $request->input('tab', 'seasons');
        if ($search_word != "") {
            $Seasons = $Seasons->where('name', 'like', '%' . $search_word . '%');
        }

        $Seasons = $Seasons->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));

        return view('dashboard.football.seasons.list', compact('Seasons', 'League', 'tab', 'GeneralWebmasterSections', 'Season'));
    }

    public function update($League_id)
    {
        app(UpdatesLeaguesAndSeasonsServices::class)->loadSeasons($League_id);

        return redirect()
            ->action([SeasonsController::class, 'index'], ['league_id' => $League_id, 'tab' => 'seasons'])
            ->with('doneMessage', __('backend.saveDone'));
    }

}
