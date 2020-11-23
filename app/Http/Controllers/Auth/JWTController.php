<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Throwable;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JWTController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generateToken($_info, $_timer)
    {
        $payload = [
            'iss' => "WEECODE", // Issuer of the token
            'email' => $_info['email'],
            'dossie' => $_info['dossie'],
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * $_timer // Expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User
     * @return mixed
     */
    public function authenticate($token)
    {
        if (!$token) {
            return [
                'error' => 'Token de autenticação nulo.'
            ];
        }

        try {
            $payload = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (Throwable $e) {
            return [
                'error' => "Seu token para download expirou!"
            ];
        } catch (Throwable $e) {
            return [
                'error' => 'Problema de autenticação. Contate o suporte técnico'
            ];
        }

        return [
            'error' => false,
            'response' => $payload
        ];
    }
}
