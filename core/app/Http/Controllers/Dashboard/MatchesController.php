<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\WebmasterSection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MatchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            return view('dashboard.leagues.rounds.details', compact('match', 'GeneralWebmasterSections', 'today'));
        } else {
            return redirect()->action([LeaguesController::class, 'index'])->with('doneMessage', __('backend.saveDone'));
        }
    }

    public function matchUpdate(Request $request, $id){
        $fixture = Fixture::findOrFail($id);

        $fixture->update([
            'is_home'   => $request->boolean('is_home'),
            'is_slider' => $request->boolean('is_slider'),
        ]);

        return redirect()->action([MatchesController::class, 'matcheRoundsEdit'], ['id' => $id])->with('doneMessage', __('backend.saveDone'));
    }
}
