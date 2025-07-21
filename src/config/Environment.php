<?php

namespace App\Config;

/**
 * Classe para carregar e gerenciar configurações do arquivo .env
 */
class Environment
{
    private static $config = [];
    private static $loaded = false;

    /**
     * Carrega as configurações do arquivo .env ou variáveis de ambiente do Railway
     */
    public static function load($path = null)
    {
        if (self::$loaded) {
            return;
        }

        // No Railway, primeiro tentar carregar das variáveis de ambiente do sistema
        if (getenv('RAILWAY_ENVIRONMENT') || getenv('MYSQL_URL')) {
            self::loadFromEnvironmentVariables();
            self::$loaded = true;
            return;
        }

        // Corrigir o caminho para procurar o .env na pasta atual ou no diretório pai
        $envFile = $path ?? __DIR__ . '/../.env';

        // Se não encontrar, tenta na pasta atual
        if (!file_exists($envFile)) {
            $envFile = __DIR__ . '/.env';
        }

        if (!file_exists($envFile)) {
            // Em produção, pode não ter arquivo .env, usar apenas variáveis de ambiente
            if (self::isProduction()) {
                self::loadFromEnvironmentVariables();
                self::$loaded = true;
                return;
            }
            throw new \Exception("Arquivo .env não encontrado. Procurado em: {$envFile}");
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignorar comentários
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Processar linha com formato KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remover aspas se existirem
                $value = trim($value, '"\'');

                self::$config[$key] = $value;

                // Definir como variável de ambiente
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                }
            }
        }

        self::$loaded = true;
    }

    /**
     * Carrega configurações das variáveis de ambiente do sistema (Railway)
     */
    private static function loadFromEnvironmentVariables()
    {
        $envVars = [
            'MYSQL_URL',
            'APP_SECRET',
            'PASSWORD_SALT',
            'APP_ENV',
            'APP_DEBUG',
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
            'MYSQLHOST',
            'MYSQLPORT',
            'MYSQLDATABASE',
            'MYSQLUSER',
            'MYSQLPASSWORD'
        ];

        foreach ($envVars as $var) {
            $value = getenv($var);
            if ($value !== false) {
                self::$config[$var] = $value;
                $_ENV[$var] = $value;
            }
        }
    }

    /**
     * Obtém uma configuração do .env
     */
    public static function get($key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config[$key] ?? $default;
    }

    /**
     * Verifica se uma configuração existe
     */
    public static function has($key)
    {
        if (!self::$loaded) {
            self::load();
        }

        return array_key_exists($key, self::$config);
    }

    /**
     * Obtém todas as configurações
     */
    public static function all()
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config;
    }

    /**
     * Obtém configurações do banco de dados
     */
    public static function database()
    {
        // Se existe MYSQL_URL (Railway), usar ela
        $mysqlUrl = self::get('MYSQL_URL');

        if ($mysqlUrl) {
            // Parse da URL: mysql://user:password@host:port/database
            $parsed = parse_url($mysqlUrl);

            return [
                'host' => $parsed['host'] ?? 'localhost',
                'port' => $parsed['port'] ?? '3306',
                'database' => ltrim($parsed['path'] ?? '/fretecalc_db', '/'),
                'username' => $parsed['user'] ?? 'root',
                'password' => $parsed['pass'] ?? '',
                'charset' => 'utf8mb4',
                'options' => [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ]
            ];
        }

        // Fallback para variáveis individuais (desenvolvimento local)
        return [
            'host' => self::get('DB_HOST', 'localhost'),
            'port' => self::get('DB_PORT', '3306'),
            'database' => self::get('DB_DATABASE', 'fretecalc_db'),
            'username' => self::get('DB_USERNAME', 'root'),
            'password' => self::get('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ];
    }

    /**
     * Verifica se está em modo debug
     */
    public static function isDebug()
    {
        return self::get('APP_DEBUG', 'false') === 'true';
    }

    /**
     * Obtém o ambiente da aplicação
     */
    public static function getEnvironment()
    {
        return self::get('APP_ENV', 'development');
    }

    /**
     * Verifica se está em produção
     */
    public static function isProduction()
    {
        return self::getEnvironment() === 'production';
    }
}
