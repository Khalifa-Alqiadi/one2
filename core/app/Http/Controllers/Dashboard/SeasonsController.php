<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\WebmasterSection;
use App\Services\ApiClientService;
use Helper;
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

    public function index()
    {
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        if (@Auth::user()->permissionsGroup->view_status) {
            $Seasons = Season::where('created_by', '=', Auth::user()->id);
        } else {
            $Seasons = Season::query();
        }
        $search_word = request()->input("q");
        if ($search_word != "") {
            $Seasons = $Seasons->where('name', 'like', '%' . $search_word . '%');
        }

        $Seasons = $Seasons->orderby('id', 'desc')->paginate(config('smartend.backend_pagination'));

        return view('dashboard.seasons.list', compact('Seasons', 'GeneralWebmasterSections'));
    }

    /**
     * Fetch seasons from SportMonks and store/update locally.
     */
    public function update()
    {
        $locale = Helper::currentLanguage()->code;
        $token  = config('services.SPORTMONKS_TOKEN');

        $page  = 1;
        $saved = 0;

        do {
            $url = "https://api.sportmonks.com/v3/football/seasons"
                . "?api_token={$token}"
                . "&page={$page}";

            $res  = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);
            $data = data_get($json, 'data', []);

            $seasons = collect($data)
                ->values();

            foreach ($seasons as $season) {
                Season::updateOrCreate(
                    ['id' => $season['id']],
                    [
                        'league_id'   => $season['league_id'],
                        'name'        => $season['name'],
                        'starting_at' => $season['starting_at'],
                        'ending_at'   => $season['ending_at'],
                        'is_current'  => $season['is_current'],
                    ]
                );

                $saved++;
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return redirect()
            ->action([SeasonsController::class, 'index'])
            ->with('doneMessage', __('backend.saveDone') . " | Updated: {$saved}");
    }
}
