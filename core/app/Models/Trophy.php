<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Trophy extends Model
{
    protected $fillable = [
        'awardable_type',
        'awardable_id',
        'sportmonks_trophy_id',
        'sportmonks_relation_id',
        'name_ar',
        'name_en',
        'country_id',
        'sport_id',
        'season',
        'date',
        'description',
        'payload_json',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'date' => 'date',
    ];

    public function awardable(): MorphTo
    {
        return $this->morphTo();
    }
}
