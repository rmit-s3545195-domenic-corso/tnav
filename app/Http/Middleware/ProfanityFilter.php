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
        if ($string)
        {
            $profanitylist = array file ("public/profanitylist.txt", FILE_SKIP_EMPTY_LINES);
            $replaceProfanity = "****";

            $fixedprofanity = str_ireplace($profanitylist, $replaceProfanity, $string);
            return $fixedprofanity;
        }
        return $next($request);
    }
}

 ?>
