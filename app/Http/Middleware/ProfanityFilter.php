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

        $rr_name = $request->rr_name;
        $rr_desc = $request->rr_desc;
        $rr_added_by = $request->rr_added_by;
        $rr_floor = $request->rr_floor;
        $profanitylist = file('/var/www/html/tnav/storage/profanitylist.txt', FILE_SKIP_EMPTY_LINES);
	
	foreach($profanitylist as $profanity) {
        if($rr_name = $profanity) {
            $request->merge(['rr_name' => "*****"]);
        }

        if($rr_desc = $profanity) {
            $request->merge(['rr_desc' => "*****"]);
        }

        if($rr_added_by = $profanity) {
            $request->merge(['rr_added_by' => "*****"]);
        }

        if($rr_floor = $profanity) {
            $request->merge(['rr_floor' => "*****"]);
        }
	}

        return $next($request);
    }
}
