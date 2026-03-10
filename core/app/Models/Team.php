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
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'sport_id');
    }
}
