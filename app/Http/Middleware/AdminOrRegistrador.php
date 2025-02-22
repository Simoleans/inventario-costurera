<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOrRegistrador
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario tiene el rol de admin o registrador
        if (Auth::check() && (Auth::user()->role->id === 1 || Auth::user()->role->id === 2)) {
            return $next($request);
        }

        // Si no tiene el rol adecuado, redirigir
        return redirect('/dashboard')->with('error', 'No tienes acceso a esta página');
    }
}
