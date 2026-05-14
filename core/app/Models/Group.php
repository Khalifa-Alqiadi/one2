<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $table = 'groups';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'league_id',
        'season_id',
        'stage_id',
        'name_ar',
        'name_en',
        'sort_order',
        'finished',
        'is_current',
        'starting_at',
        'ending_at',
    ];

    protected $casts = [
        'finished'    => 'boolean',
        'is_current'  => 'boolean',
        'starting_at' => 'datetime',
        'ending_at'   => 'datetime',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'group_id');
    }
}
