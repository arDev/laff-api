<?php

namespace Src\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private static $secretKey = 'tu_clave_secreta'; // Cambia esto por una clave segura
    private static $algorithm = 'HS256';

    public static function generateToken($userId)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 60; // Token válido por 1 hora

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $userId
        ];

        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }

    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
?>