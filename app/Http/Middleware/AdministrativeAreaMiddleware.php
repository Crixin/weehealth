<?php

namespace App\Http\Middleware;

use Closure;
use App\Classes\Constants;
use Illuminate\Support\Facades\{Auth, Log};

class AdministrativeAreaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeDetails = $request->route()->getAction();
        
        if( Auth::user()->administrador ) {
            return $next($request);
        } else {
            
            // Exceções à regra (que é: caiu no else, NÃO tem permissão para continuar)
            $canEditHimself = array('usuario.editar', 'usuario.alterar', 'usuario.alterarSenha');
            if( in_array($routeDetails['as'], $canEditHimself) ) {
                
                if( $request->getMethod() === "GET" ) {
                    $params = $request->route()->parameters();
                    if( $params['id'] == Auth::user()->id ) {
                        return $next($request);
                    }
                } else if( $request->getMethod() === "POST" ) {
                    if( $request->idUsuario == Auth::user()->id ) {
                        return $next($request);
                    }
                }
            }

            return response()->view('errors.403');
        }

    }
}
