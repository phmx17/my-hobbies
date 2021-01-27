<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
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
      if(Auth::user() && auth()->user()->role !=='admin') { // auth() method does not exist if user is not logged in; php evalutes from left to right
        abort(403, 'Only for Administrators');
      }  
      return $next($request);
    }
}
