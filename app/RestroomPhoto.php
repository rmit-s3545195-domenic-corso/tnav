<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestroomPhoto extends Model {
    public static function getPhotoFileName($binary) {
        /* Use crc32 hash for shorter name */
        return hash('crc32', $binary);
    }
}
