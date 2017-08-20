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

        foreach ($input as $value)
        {
            if ($value)
            {
            $profanitylist = array file('/var/www/html/tnav/storage/app/public/profanitylist.txt', FILE_SKIP_EMPTY_LINES);
            $replaceProfanity = "****";

            $fixedprofanity = str_ireplace($profanitylist, $replaceProfanity, $value);
            return $fixedprofanity;
            }
        }
        return $next($request);
    }
}
