<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveSeason extends Model
{
    protected $keyType = 'int'; // أو 'string' إذا كان UUID
    protected $guarded = [];
}
