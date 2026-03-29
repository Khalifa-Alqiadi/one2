<?php

use App\Http\Controllers\Custom\CustomController;
use App\Http\Controllers\Football\LeaguesController;
use App\Http\Controllers\Custom\LeagueTabsController;
use App\Http\Controllers\Football\FixturesController;
use App\Http\Controllers\Custom\MatchesController;
use Illuminate\Support\Facades\Route;

// private route example ( require login )
/*
Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth', 'LanguageSwitcher']], function () {
    Route::get('/custom-page', [CustomController::class, 'custom_page']);
});
*/

// public route example
// Route::get('/custom-page', [CustomController::class, 'custom_page']);
// Route::get('/ucl', [LeagueController::class, 'index'])->name('ucl');
Route::get('/leagues', [LeaguesController::class, 'index'])->name('leagues');
Route::get('/leagues/{id?}/rounds', [LeaguesController::class, 'rounds'])->name('league.rounds');
// Route::get('/round-odds/{roundId}', [LeagueController::class, 'round'])
    // ->name('round.odds');
// Route::get('/standings/{standingsID}', [LeagueController::class, 'standings']);
// Route::get('/league/{leagueId}/tabs', [LeagueTabsController::class, 'index'])
    // ->name('league.tabs');
//
Route::get('/club/{teamId}', [LeagueTabsController::class, 'show'])
    ->name('club.show');

Route::get('/matches', [FixturesController::class, 'index'])
    ->name('matches');

// JSON endpoint used by client-side polling to refresh today's matches
Route::get('/matches/today-json', [FixturesController::class, 'todayJson'])
    ->name('matches.today.json');


// proxy live endpoint (يطلب SportMonks من السيرفر)
Route::get('/fixtures/live-proxy', [MatchesController::class, 'liveProxy'])
    ->name('fixtures.live.proxy');

Route::get('/live/league/{leagueId}', [LeaguesController::class, 'liveLeague'])->name('live.league');

Route::get('/fixtures/{id}/live', [MatchesController::class, 'fixtureLiveDetails'])
    ->name('fixture.live.details');

Route::get('/match/details/{id}', [MatchesController::class, 'showFixture'])
    ->name('match.show');




