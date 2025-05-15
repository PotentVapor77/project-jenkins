<?php
use Vendor\VentasPhp\Core\Router;
use Vendor\VentasPhp\Core\AuthMiddleware;
use Vendor\VentasPhp\Core\Response;

$namespace = 'Vendor\\VentasPhp\\Controllers\\';

return function (Router $router) use ($namespace) {
    // Creador de instancias de controladores
    $controller = function(string $className) use ($namespace) {
        $fullClassName = $namespace . $className;
        return new $fullClassName();
    };

    // Ruta raíz redirige a login
    //$router->add('GET', '/', fn() => new Response('', 302, ['Location' => '/login']));

    $router->add('GET', '/', function() {
        error_log("Root route accessed, redirecting to login");
        return new Response('Redirecting to login', 302, ['Location' => '/login']);
    });



    // Rutas públicas
    $router->add('GET', '/login', fn() => $controller('AuthController')->showLogin());
    $router->add('POST', '/login', fn() => $controller('AuthController')->login());
    $router->add('GET', '/logout', fn() => $controller('AuthController')->logout());

    

    // Middleware de autenticación
    $authMiddleware = fn(callable $handler) => function(...$params) use ($handler) {
        AuthMiddleware::handle();
        return $handler(...$params);
    };

    // Función para rutas protegidas
    $protected = function(string $method, string $route, string $controllerAction) use ($router, $controller, $authMiddleware) {
        list($controllerName, $action) = explode('@', $controllerAction);
        $router->add($method, $route, $authMiddleware(fn(...$params) => $controller($controllerName)->$action(...$params)));
    };


    // Rutas protegidas
    // Dashboard y usuarios
    $protected('GET', '/dashboard', 'DashboardController@index');
    $protected('GET', '/users', 'UserController@index');
    $protected('GET', '/users/create', 'UserController@showCreate');
    $protected('POST', '/users/create', 'UserController@create');
    $protected('GET', '/users/edit/{id}', 'UserController@showEdit');
    $protected('POST', '/users/edit/{id}', 'UserController@edit');
    $protected('POST', '/users/delete/{id}', 'UserController@delete');
    $protected('POST', '/users/update-role/{id}', 'UserController@updateRole');

    // Clientes
    $protected('GET', '/clients', 'ClientController@index');
    $protected('GET|POST', '/clients/create', 'ClientController@create');
    $protected('GET|POST', '/clients/edit/{id}', 'ClientController@edit');
    $protected('POST', '/clients/delete/{id}', 'ClientController@delete');
    $protected('POST', '/sales/cancel', 'ClientController@cancelSale');

    // Perfil
    $protected('GET|POST', '/profile/change-password', 'ProfileController@changePassword');

    // Productos
    $protected('GET', '/products', 'ProductController@index');
    $protected('GET|POST', '/products/create', 'ProductController@create');
    $protected('GET|POST', '/products/edit/{id}', 'ProductController@edit');
    $protected('POST', '/products/delete/{id}', 'ProductController@delete');

    // Ventas
    $protected('GET', '/sales', 'SaleController@create');
    $protected('POST', '/sales/set-client', 'SaleController@setClient');
    $protected('POST', '/sales/cancel', 'SaleController@cancel');

    // Reportes
    $protected('GET|POST', '/reports', 'ReportController@sales');

    // Rutas protegidas
    $protected('GET', '/profile', 'ProfileController@show');
    $protected('GET|POST', '/profile/change-password', 'ProfileController@changePassword');



};

