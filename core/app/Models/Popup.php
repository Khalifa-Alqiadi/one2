<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    public function form()
    {
        return $this->belongsTo('App\Models\WebmasterSection', 'form_id');
    }
}
