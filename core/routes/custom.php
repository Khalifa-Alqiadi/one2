<?php

use App\Http\Controllers\Custom\CustomController;
use App\Http\Controllers\Football\LeaguesController;
use App\Http\Controllers\Custom\LeagueTabsController;
use App\Http\Controllers\FixturesController as ControllersFixturesController;
use App\Http\Controllers\Football\FixturesController;
use App\Http\Controllers\Football\MatchesController;
use App\Http\Controllers\Football\LivescoresController;
use App\Http\Controllers\Football\PlayersController;
use App\Http\Controllers\Football\TeamsController;
use App\Http\Controllers\TimezoneController;
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
Route::get('/leagues', [LeaguesController::class, 'index'])->name('leagues.show');
Route::get('/leagues/{id?}/rounds', [LeaguesController::class, 'rounds'])->name('league.rounds');
// Route::get('/round-odds/{roundId}', [LeagueController::class, 'round'])
    // ->name('round.odds');
// Route::get('/standings/{standingsID}', [LeagueController::class, 'standings']);
// Route::get('/league/{leagueId}/tabs', [LeagueTabsController::class, 'index'])
    // ->name('league.tabs');
//
Route::get('/club/{teamId}', [LeagueTabsController::class, 'show'])
    ->name('club.show');

Route::get('/today-matches', [MatchesController::class, 'index'])
    ->name('matches');
Route::get('/{lang?}/matches', [MatchesController::class, 'index'])
    ->name('matchesLang');

// JSON endpoint used by client-side polling to refresh today's matches
Route::get('/matches/today-json', [FixturesController::class, 'todayJson'])
    ->name('matches.today.json');

Route::post('/matches/filter', [MatchesController::class, 'filterAjax'])
    ->name('matches.filter');

// proxy live endpoint (يطلب SportMonks من السيرفر)
Route::get('/fixtures/live-proxy', [LivescoresController::class, 'liveProxy'])
    ->name('fixtures.live.proxy');

Route::get('/live/league/{leagueId}', [LeaguesController::class, 'liveLeague'])->name('live.league');

Route::get('/fixtures/{id}/live', [MatchesController::class, 'fixtureLiveDetails'])
    ->name('fixture.live.details');

Route::get('/match/details/{id}', [MatchesController::class, 'showFixture'])
    ->name('match.show');

Route::get('/live-matches', [LivescoresController::class, 'index'])
    ->name('live.matches');


Route::get('/fixture/{id}/commentary', [MatchesController::class, 'commentary'])->name('commentary');
Route::post('/set-timezone', [TimezoneController::class, 'set'])->name('set.timezone');

// Teams
Route::get('/teams/{id}/details', [TeamsController::class, 'show'])->name('team.details');
Route::get('/players/{id}/details', [PlayersController::class, 'show'])->name('players.details');
Route::post('/matches-by-date', [MatchesController::class, 'matchesByDate'])->name('matches.by.date');

Route::get('/fixtures', [ControllersFixturesController::class, 'index'])->name('fixtures.index');
Route::get('/fixtures/{id}', [ControllersFixturesController::class, 'show'])
    ->whereNumber('id')
    ->name('fixtures.show');





