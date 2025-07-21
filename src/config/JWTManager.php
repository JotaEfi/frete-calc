<?php

namespace App\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTManager
{
    private static $secret;
    private static $algorithm;
    private static $expiration;
    private static $issuer;

    public static function init()
    {
        Environment::load();
        self::$secret = Environment::get('JWT_SECRET', 'default-secret-key');
        self::$algorithm = Environment::get('JWT_ALGORITHM', 'HS256');
        self::$expiration = (int)Environment::get('JWT_EXPIRATION', 86400);
        self::$issuer = Environment::get('JWT_ISSUER', 'fretecalc.com');
    }

    /**
     * Gera um token JWT para o usuário
     */
    public static function generateToken($user)
    {
        self::init();

        $issuedAt = time();
        $expiration = $issuedAt + self::$expiration;

        $payload = [
            'iss' => self::$issuer,           // Emissor
            'iat' => $issuedAt,               // Emitido em
            'exp' => $expiration,             // Expira em
            'user_id' => $user['id'],         // ID do usuário
            'email' => $user['email'],        // Email do usuário
            'name' => $user['name'],          // Nome do usuário
            'role' => $user['role']           // Role do usuário
        ];

        return JWT::encode($payload, self::$secret, self::$algorithm);
    }

    /**
     * Valida e decodifica um token JWT
     */
    public static function validateToken($token)
    {
        try {
            self::init();

            $decoded = JWT::decode($token, new Key(self::$secret, self::$algorithm));
            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception('Token inválido: ' . $e->getMessage());
        }
    }

    /**
     * Extrai token do header Authorization
     */
    public static function getTokenFromHeader()
    {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Middleware para verificar autenticação
     */
    public static function requireAuth()
    {
        $token = self::getTokenFromHeader();

        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não fornecido']);
            exit;
        }

        try {
            $user = self::validateToken($token);
            return $user;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
