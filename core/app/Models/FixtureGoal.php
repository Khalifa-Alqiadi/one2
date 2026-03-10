<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixtureGoal extends Model
{
    protected $fillable = [
        'id',
        'fixture_id',
        'team_id',
        'player_id',
        'assist_id',
        'minute',
        'extra_minute',
        'result',
        'detail',
        'is_own_goal',
        'is_penalty',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
