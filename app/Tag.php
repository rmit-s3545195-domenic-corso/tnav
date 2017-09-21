<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function restrooms() {
        return $this->belongsToMany(Restroom::class);
    }

    public static function getIdMapFunction($tag) {
        return $tag->id;
    }
}
