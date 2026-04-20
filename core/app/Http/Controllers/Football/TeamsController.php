<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Models\Team;
use App\Services\SyncSportmonksTeamByIdService;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function show($id){
        $team = Team::find($id);

        if (!$team || $team->type === null) {
            (new SyncSportmonksTeamByIdService(config('services.SPORTMONKS_TOKEN')))
                ->handle($id);
        }

        // إعادة جلب الفريق بعد التحديث
        $team = Team::with([
            'venue',
            'players',
            'players.country'
        ])->findOrFail($id);
        $mainMatches = $team?->matches()
            ->where('is_finished', 0)
            ->whereHas('season', function ($q) {
                $q->where('is_current', true);
            })
            ->orderBy('starting_at', 'asc')
            ->limit(3)
            ->get();
        // $mainMatches = Fixture::where(function ($query) use ($team) {
        //     $query->where('home_team_id', $team->id)
        //       ->orWhere('away_team_id', $team->id);
        //     })
        //     ->where('is_finished', 0)
        //     ->whereHas('season', function ($q) {
        //         $q->where('is_current', true);
        //     })
        //     ->orderBy('starting_at', 'asc')
        //     ->limit(3)
        //     ->get();

        return view('frontEnd.football.teams.show', compact('team', 'mainMatches'));
    }
}
