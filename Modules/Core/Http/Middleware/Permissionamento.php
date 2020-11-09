<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Permissionamento
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

        foreach (Auth::user()->corePerfil->corePermissoes ?? [] as $per) {
            if (in_array($per->nome, $permissoes)) {
                return $next($request);
            }
        }

        if (Auth::user()->administrador) {
            foreach ($permissoes as $permissao) {
                if ($permissao == "administrador") {
                    return $next($request);
                }
            }
        }

        return redirect('/');
    }
}
