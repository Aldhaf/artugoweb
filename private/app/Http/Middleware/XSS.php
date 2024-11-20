<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function(&$input) {
            $input = static::cleanxss($input);
        });
        $request->merge($input);
        return $next($request);
    }

    public static function cleanxss($text) {
        $text = strip_tags($text);
        $text = str_replace('">', '', $text);
        return $text;
    }
}
