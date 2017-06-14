<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthAdmin
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
        if( \Auth::check() && \Auth::user()->isAdmin() ){
            return $next($request);
        }else{
            return redirect( '/login?url=' . urlencode( $_url ) );
        }
    }
}
