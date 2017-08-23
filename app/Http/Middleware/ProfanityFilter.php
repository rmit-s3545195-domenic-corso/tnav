<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfanityFilter
{
    /**
    *Handle an incoming request
    *
    *@param \Illuminate\Http\Request $request
    *@param \Closure $next
    *@return mixed
    *
    */
    public function handle($request, Closure $next) {

        $rr_name = Input::get('rr_name');
        $rr_desc = Input::get('rr_desc');
        $rr_added_by = Input::get('rr_added_by');
        $rr_floor = Input::get('rr_floor');
        $profanitylist = file('/var/www/html/tnav/storage/profanitylist.txt', FILE_SKIP_EMPTY_LINES);

        if(in_array($rr_name, $profanitylist)) {
            Input::merge(['rr_name' => '"*****"']);
        }

        if(in_array($rr_desc, $profanitylist)) {
            Input::merge(['rr_desc' => '"*****"']);
        }

        if(in_array($rr_added_by, $profanitylist)) {
            Input::merge(['rr_added_by' => '"*****"']);
        }

        if(in_array($rr_floor, $profanitylist)) {
            Input::merge(['rr_floor' => '"*****"']);
        }

        return $next($request);
    }
}
