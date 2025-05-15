<?php
require 'vendor/autoload.php';
require 'config/constants.php';

try {
    $db = Vendor\VentasPhp\Config\Database::getInstance();
    echo "¡Conexión exitosa!<br>";
    
    // Prueba consulta simple
    $usuarios = $db->query("SELECT * FROM usuarios")->fetchAll();
    echo "Usuarios encontrados: " . count($usuarios);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}