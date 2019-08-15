<?php

namespace App\Http\Middleware;

use Closure;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->type == $role || $request->user()->type == 'admin') {
            return $next($request);
        }

        return abort(404);
    }
}
