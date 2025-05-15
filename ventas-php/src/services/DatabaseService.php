<?php
namespace Vendor\VentasPhp\Services;

use PDO;
use PDOException;

class DatabaseService {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                // Definir los valores directamente
                $host = 'localhost';
                $dbname = 'ventas_php';
                $username = 'root';
                $password = ''; 
                $charset = 'utf8mb4';

                // Crear la conexión PDO
                self::$connection = new PDO(
                    'mysql:host=' . $host . ';dbname=' . $dbname,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
