<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $table = 'rounds';

    protected $fillable = [
        'id',
        'league_id',
        'season_id',
        'stage_id',
        'name',
        'finished',
        'is_current',
        'games_in_current_week',
        'starting_at',
        'ending_at'
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class, 'round_id', 'id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

}
