<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuth
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
        $_url = $request->getRequestUri();
        if( !\Auth::check() ){
            return redirect( '/login?url=' . urlencode( $_url ) );
        }
        return $next($request);
    }
}
