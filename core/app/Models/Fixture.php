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
        'venue_id',
        'home_team_id',
        'away_team_id',
        'starting_at',
        'state_id',
        'stage_id',
        'state_name',
        'state_code',
        'home_score',
        'away_score',
        'is_finished',
        'ft_home_score',
        'ft_away_score',
        'minute',
        'payload',
        'is_slider',
        'is_home',
        'events_json',
        'statistics_json',
        'lineups_json',
        'win_probabilities_json',
        'details_synced_at',
        'tv_stations_json',
        'injuries_json',
        'suspensions_json',
        'venue_json',
    ];

    protected $casts = [
        'events_json'            => 'array',
        'statistics_json'        => 'array',
        'lineups_json'           => 'array',
        'win_probabilities_json' => 'array',
        'details_synced_at'      => 'datetime',
        'starting_at'            => 'datetime',
        'payload' => 'array',
        'tv_stations_json' => 'array',
        'injuries_json' => 'array',
        'suspensions_json' => 'array',
        'venue_json' => 'array',
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

    public function round()
    {
        return $this->belongsTo(Round::class, 'round_id', 'id');
    }


}
