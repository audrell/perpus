<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->member && Auth::user()->member->status == 0) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun kamu sedang nonaktif!');
            }
        }

        return $next($request);
    }
}
