<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restroom extends Model
{
    public static function getValidationRules() : array {
        $wordRegex = '/^[\w \'\.]+$/';
        $latLngRegex = '/^-?\d+\.\d+$/';

        return [
            'rr_name' => 'required|max:255|regex:'.$wordRegex,
            'rr_desc' => 'nullable|max:255|regex:'.$wordRegex,
            'rr_lat' => 'required|max:50|regex:'.$latLngRegex,
            'rr_lng' => 'required|max:50|regex:'.$latLngRegex,
            'rr_floor' => 'nullable|max:20|regex:'.$wordRegex,
            'rr_added_by' => 'nullable|max:70|regex:'.$wordRegex
        ];
    }
}
