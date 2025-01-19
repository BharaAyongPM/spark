<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->roles->contains('name_roles', $role)) {
            return $next($request);
        }

        // Redirect jika user tidak memiliki peran yang sesuai
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
