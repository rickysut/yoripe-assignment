<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class UserRole
{
    use AuthenticatesUsers {
        logout as performLogout;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userRole)
    {
        if (auth()->user()->role == $userRole) {
            return $next($request);
        }

        $this->performLogout($request);
        return redirect('/login')->withErrors('You don\'t have permission to access for this page.');
        //return response()->json(['You don\'t have permission to access for this page.']);
        /* return response()->view('errors.check-permission'); */
    }
}