<?php

namespace App\Http\Controllers\Football;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    public function show($id){
        $player = Player::findOrFail($id);
        return view('frontEnd.football.players.show', compact('player'));
    }
}
