<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AuthenticateAPI
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

    if (($request->header('token') || $request->query('token')) == '99a38c63-455b-4bb8-ac4c-4e8077feb4f3') {
      return $next($request);
    } else {
      $response = [
        'status' => false,
        'message' => 'Token API Invalid',
      ];

      return response()->json($response, 403);
    }
  }
}
