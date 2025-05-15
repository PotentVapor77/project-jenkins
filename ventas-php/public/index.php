<?php

use Vendor\VentasPhp\Core\Router;
use Vendor\VentasPhp\Core\Response;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Vendor\VentasPhp\Config\Database;


define('BASE_PATH', realpath(__DIR__ . '/..'));

// -------------------
// CONFIGURACIÓN INICIAL
// -------------------

// Cargar constantes
require BASE_PATH . '/config/constants.php';

// Autoload de Composer (debe ir después de las constantes si alguna clase las usa)
require BASE_PATH . '/vendor/autoload.php';

// Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// -------------------
// CONFIG ESPECIAL PARA PHP INTEGRADO
// -------------------
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $file = __DIR__ . $path;
    if ($path !== '/' && file_exists($file)) {
        return false; // Servir archivo estático
    }
}

// Configurar entorno CLI (por si se usa PHP desde la terminal)
if (php_sapi_name() === 'cli') {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $argv[1] ?? '/';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

// -------------------
// SESIONES Y ROUTING
// -------------------

session_start();

$basePath = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
$router = new Router($basePath);

// Cargar rutas
$defineRoutes = require BASE_PATH . '/config/routes.php';
$defineRoutes($router);

// Ruta no encontrada
$router->setNotFound(function () {
    return new Response('Página no encontrada', 404);
});

// -------------------
// EJECUCIÓN
// -------------------

try {
    // Verificar conexión a la base de datos
    $pdo = Database::getInstance();

    // Ejecutar router
    $response = $router->dispatch();
    $response->send();

} catch (\PDOException $e) {
    http_response_code(500);

    $log = new Logger('database');
    $log->pushHandler(new StreamHandler(BASE_PATH . '/storage/logs/database.log', Logger::CRITICAL));
    $log->critical('Error de conexión a base de datos', ['exception' => $e]);

    echo "\n Error de base de datos: " . $e->getMessage();

} catch (\Throwable $e) {
    http_response_code(500);

    $log = new Logger('app');
    $log->pushHandler(new StreamHandler(BASE_PATH . '/storage/logs/app.log', Logger::CRITICAL));
    $log->critical('Error del servidor', ['exception' => $e]);

    echo "Error del servidor: " . $e->getMessage();
}