<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permissao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissoes)
    {
        if (is_null(Auth::user())) {
            return redirect('/login');
        }

        if (Auth::user()->administrador) {
            return $next($request);
        }
        
        if (count(array_intersect($permissoes, Auth::user()->corePerfil->permissoes))) {
            return $next($request);
        }
        
        return redirect('/');
    }
}
