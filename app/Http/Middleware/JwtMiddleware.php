<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token');

        if (!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $request->attributes->add(['credentials' => $credentials]);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => "Seu token de autenticação expirou!"
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Problema de autenticação.'
            ], 400);
        }
        return $next($request);
    }
}
