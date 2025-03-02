<?php

namespace Src\Controllers;

use Src\Auth\Auth;

class AuthController
{
    public function login($username, $password)
    {
        // Aquí deberías validar el usuario y la contraseña contra tu base de datos
        // Este es un ejemplo básico
        if ($username === 'admin' && $password === 'admin') {
            $userId = 1; // ID del usuario
            $token = Auth::generateToken($userId);
            return ['token' => $token];
        } else {
            return ['error' => 'Credenciales inválidas'];
        }
    }

    public function validateToken($token)
    {
        $payload = Auth::validateToken($token);
        if ($payload) {
            return ['valid' => true, 'payload' => $payload];
        } else {
            return ['valid' => false];
        }
    }
}
?>