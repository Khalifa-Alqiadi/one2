<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', // نفس sportmonks id
        'sport_id',
        'country_id',
        'name_ar',
        'name_en',
        'short_code',
        'image_path',
        'status',
        'is_home',
        'row_no',
        'current_season_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class, 'league_id', 'id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class, 'league_id', 'id');
    }

    public function stages()
    {
        return $this->hasMany(Stage::class, 'league_id');
    }

    public function matches()
    {
        return $this->hasMany(Fixture::class, 'league_id', 'id');
    }
}
