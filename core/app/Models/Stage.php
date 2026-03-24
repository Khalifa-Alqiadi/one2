<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $table = 'stages';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'league_id',
        'season_id',
        'type_id',
        'name_ar',
        'name_en',
        'type_name',
        'sort_order',
        'finished',
        'is_current',
        'starting_at',
        'ending_at',
        'payload',
    ];

    protected $casts = [
        'finished'    => 'boolean',
        'is_current'  => 'boolean',
        'starting_at' => 'datetime',
        'ending_at'   => 'datetime',
        'payload'     => 'array',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class,'stage_id');
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class, 'stage_id');
    }
}
