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

        $rr_name = $request->input('rr_name');
        $rr_desc = $request->input('rr_desc');
        $rr_added_by = $request->input('rr_added_by');
        $rr_floor = $request->input('rr_floor');
        $profanitylist = file('/var/www/html/tnav/storage/profanitylist.txt', FILE_SKIP_EMPTY_LINES);

        if(in_array($rr_name, $profanitylist)) {
            $request->merge(['rr_name' => "*****"]);
        }

        if(in_array($rr_desc, $profanitylist)) {
            $request->merge(['rr_desc' => "*****"]);
        }

        if(in_array($rr_added_by, $profanitylist)) {
            $request->merge(['rr_added_by' => "*****"]);
        }

        if(in_array($rr_floor, $profanitylist)) {
            $request->merge(['rr_floor' => "*****"]);
        }

        return $next($request);
    }
}
