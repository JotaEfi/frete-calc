<?php

namespace App\Config;

use App\Config\Environment;

/**
 * Classe para gerenciar conexões com o banco de dados
 */
class Database
{
    private static $connection = null;

    /**
     * Obtém a conexão com o banco de dados usando configurações do .env
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                $config = Environment::database();

                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";

                self::$connection = new \PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );

                if (Environment::isDebug()) {
                    error_log("Conexão com banco de dados estabelecida com sucesso");
                }

            } catch (\PDOException $e) {
                $error = "Erro na conexão com o banco de dados: " . $e->getMessage();

                if (Environment::isDebug()) {
                    error_log($error);
                    throw new \Exception($error);
                } else {
                    throw new \Exception("Erro na conexão com o banco de dados");
                }
            }
        }

        return self::$connection;
    }

    /**
     * Testa a conexão com o banco
     */
    public static function testConnection()
    {
        try {
            $pdo = self::getConnection();
            $pdo->query("SELECT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}