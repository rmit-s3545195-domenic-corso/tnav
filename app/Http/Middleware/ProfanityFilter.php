<?php

namespace App\Http\Middleware;

use Closure;

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
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        $profanitylist = file('/var/www/html/tnav/storage/profanitylist.txt', FILE_SKIP_EMPTY_LINES);
        $replaceProfanity = "****";

        foreach($input as $value)
        {
            if(in_array($value, $profanitylist)
            {
            $fixedprofanity = str_ireplace($value, $replaceProfanity, $value);
            return $fixedprofanity;
            }
        }
        return $next($request);
    }
}
