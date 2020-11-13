<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use App\Classes\Constants;
use Illuminate\Support\Facades\Auth;

class OnlyAllowSuperAdminsMiddleware
{
    /**
     * Handle an incoming request.
     * Descrição: middleware para aquelas áreas que são extremamente restritas, ou sejas, apenas os SUPERADMINISTRADORES podem alterar (até o momento, tela de administradores é o único caso)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if( in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) ) {
            return $next($request);
        } else {
            return response()->view('errors.403');
        }

    }
}
