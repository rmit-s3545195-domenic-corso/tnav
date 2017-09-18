<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['restroom_id', 'author', 'body', 'stars'];
    
    public static function getValidationRules() : array {
        $authorRegex = '/^[\w\s]+$/';
        $bodyRegex = '/^[\*\w\s\,\d\'!]+$/';

        return [
            'restroom_id' => 'required|exists:restrooms,id',
            'author' => 'nullable|max:80|min:2|regex:'.$authorRegex,
            'body' => 'nullable|max:255|min:10|regex:'.$bodyRegex,
            'stars' => 'required|integer|between:1,5'
        ];
    }
}
