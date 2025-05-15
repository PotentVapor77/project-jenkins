<?php
require __DIR__ . '/../vendor/autoload.php';


use Vendor\VentasPhp\Config\Database;

try {
    $db = Database::getInstance();
    echo "¡Autoload funciona correctamente!";
    var_dump($db);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}