<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ChangeUser
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
        $usuario =  strtolower(Auth::user()->username);
        DB::purge('pgsql');
        Config::set('database.connections.pgsql.username', $usuario);
        Config::set('database.connections.pgsql.password', Auth::user()->password);
        DB::reconnect('pgsql');

        return $next($request);
    }
}
