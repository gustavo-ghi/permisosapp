<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado Y si su rol es 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Si es admin, permite que la petición continúe
            return $next($request);
        }

        // Si no es admin, redirige al home con un error.
        return redirect('/home')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}

