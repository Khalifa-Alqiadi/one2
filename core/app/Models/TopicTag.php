<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicTag extends Model
{
    use HasFactory;

    //Relation to Tags
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id');
    }
}
