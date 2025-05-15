<?php
namespace Vendor\VentasPhp\Config;

class Database {
    private static ?\PDO $instance = null;  

    

    private function __construct() {
        // Verifica que las constantes estén definidas
        if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_CHARSET')) {
            throw new \RuntimeException('Database configuration constants are not defined');
        }

        $config = [
            'host' => DB_HOST,
            'dbname' => DB_NAME,
            'username' => DB_USER,
            'password' => defined('DB_PASS') ? DB_PASS : '',
            'charset' => DB_CHARSET,
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ];

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            self::$instance = new \PDO(
                $dsn, 
                $config['username'], 
                $config['password'], 
                $config['options']
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance(): \PDO {
        if (self::$instance === null) {
            new self(); 
        }
        return self::$instance;
    }
}