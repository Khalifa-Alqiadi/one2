<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    public $incrementing = false;

    protected $keyType = 'int'; // أو 'string' إذا كان UUID
    protected $guarded = [];
}
