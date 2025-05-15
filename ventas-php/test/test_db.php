<?php
require __DIR__.'/vendor/autoload.php';

use Vendor\VentasPhp\Config\Database;

try {
    $db = Database::getInstance();
    echo "¡Conexión exitosa!\n";
    var_dump($db);
} catch (Exception $e) {
    echo "Error: ".$e->getMessage()."\n";
    echo "Ruta autoload: ".__DIR__.'/vendor/autoload.php'."\n";
    echo "¿Existe? ".(file_exists(__DIR__.'/vendor/autoload.php') ? 'Sí' : 'No')."\n";
}