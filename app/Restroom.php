<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restroom extends Model
{
    public function photos() : HasMany {
        return $this->hasMany('App\RestroomPhoto');
    }

    public function reviews() : HasMany {
        return $this->hasMany('App\Review');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag');
    }

    public function stars() : int {
        $starsTotal = 0;
        $count = 0;

        foreach ($this->reviews as $r) {
            $starsTotal += $r->stars;
            $count++;
        }

        if ($starsTotal == 0 || $count == 0) {
            return 0;
        }

        return round($starsTotal / $count);
    }

    public static function getValidationRules() : array {
        $textRegex = '/^[\*\w\s\,\d\']+$/';
        $descRegex = '/^[\*\w\s\,\d\'!]+$/';
        $latLngRegex = '/^-?\d+\.\d+$/';

        return [
            'rr_name' => 'required|max:255|min:5|regex:'.$textRegex,
            'rr_desc' => 'nullable|max:255|min:10|regex:'.$descRegex,
            'rr_lat' => 'required|max:50|regex:'.$latLngRegex,
            'rr_lng' => 'required|max:50|regex:'.$latLngRegex,
            'rr_floor' => 'nullable|max:20|regex:'.$textRegex,
            'rr_added_by' => 'nullable|max:70|min:2|regex:'.$textRegex,
        ];
    }

}
