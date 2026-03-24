<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    protected $fillable = [
        'id',
        'league_id',
        'season_id',
        'round_id',
        'home_team_id',
        'away_team_id',
        'starting_at',
        'state_id',
        'stage_id',
        'state_name',
        'home_score',
        'away_score',
        'is_finished',
        'ft_home_score',
        'ft_away_score',
        'minute',
        'payload',
        'is_slider',
        'is_home'
    ];

    protected $casts = [
        'starting_at' => 'datetime',
        'payload' => 'array',
    ];

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'id');
    }


}
