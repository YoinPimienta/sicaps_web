<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('jwt_token')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado. Se requiere iniciar sesión.'], 401);
            }
            return redirect()->guest(route('login'))->with('error', 'Debes iniciar sesión para acceder al sistema.');
        }

        return $next($request);
    }
}
