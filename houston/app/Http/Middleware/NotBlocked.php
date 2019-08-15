<?php

namespace App\Http\Middleware;

use Closure;

class NotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->isBlocked()) {
            // the User is blocked!
            return response()->view('user.blocked');
        }

        return $next($request);
    }
}
