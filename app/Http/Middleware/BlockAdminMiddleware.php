<?php

namespace App\Http\Middleware;

use Closure;
use App\Classes\Constants;
use Illuminate\Support\Facades\Auth;

class BlockAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($_request, Closure $_next)
    {
        // Parâmetros recebidos no request (geralmente será o id do usuário via GET)
        
        // Se o id do usuário que se está tentando editar via sistema é um dos superAdministradores
        if ($_request->id == 1 && Auth::id() != 1) {
            return response()->view('errors.403');
        } else {
            return $_next($_request);
        }
    }
}
