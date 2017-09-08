<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Storage;

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
        /* If there is no input in the request then don't run */
        if (!count($request->all())) {
	         return $next($request);
	      }

        $profanityList = file(storage_path('profanitylist.txt'), FILE_SKIP_EMPTY_LINES);
	      $profanityList = array_map("trim", $profanityList);

        /* Goes  through each request input first*/
	      foreach($request->except('_token') as $k => $v) {
          if (!is_string($v)) {
            continue;
          }
          $modifiedValue = $v;
          /* Goes through each profanity */
	        foreach($profanityList as $p) {
		          if(strpos(strtolower($v), $p) !== false) {
	               /* Replace characters with stars */
                $modifiedValue = str_replace($p, self::stars(strlen($p)), $modifiedValue);
		          }
	         }
           $request->merge([$k => $modifiedValue]);
	      }
        return $next($request);
      }

    /* Function to convert characters into stars */
    private function stars($amount) {
        $stars = "";
        for ($i = 0; $i < $amount; $i++) {
            $stars .= "*";
        } return $stars;
    }
}
