<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use HasFactory;

    // IDs come from SportMonks and are not auto-incrementing here
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'league_id',
        'name',
        'starting_at',
        'ending_at',
        'is_current',
    ];
}
