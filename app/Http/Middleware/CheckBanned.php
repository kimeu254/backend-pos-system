<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->status )
        {
            Auth::user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Account suspended, please contact your admin.'
            ]);
        }

        return $next($request);
    }
}
