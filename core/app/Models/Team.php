<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false;

    protected $keyType = 'int'; // أو 'string' إذا كان UUID
    protected $fillable = [
        'id', // استخدام id من SportMonks كـ primary key
        'country_id',
        'venue_id',
        'sport_id',
        'name_ar',
        'name_en',
        'short_code',
        'image_path',
        'founded',
        'row_no',
        'status',
        'major_national_teams',
        'major_competitions',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function trophies()
    {
        return $this->morphMany(Trophy::class, 'awardable');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'team_players')
            ->withPivot([
                'season_id',
                'position_id',
                'detailed_position_id',
                'jersey_number',
                'from_date',
                'to_date',
                'is_current',
                'is_captain',
                'transfer_id',
            ])
            ->withTimestamps();
    }

    public function venue(){
        return $this->belongsTo(Venue::class, 'venue_id');
    }
    public function topic(){
        return $this->hasMany(Topic::class, 'team_id');
    }

    public function matches()
    {
        return Fixture::where(function ($query) {
            $query->where('home_team_id', $this->id)
              ->orWhere('away_team_id', $this->id);
            });
    }
}
