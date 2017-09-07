<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restroom extends Model
{
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
            'rr_photos.*' => 'mimes:jpg,jpeg,png'
        ];
    }
}
