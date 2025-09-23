<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Optional
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (!session()->has('admin')&& !session()->has('employee_logged_in')) {
            return redirect('/login')->with('error', '');
        }

        return $next($request);
    }
}
