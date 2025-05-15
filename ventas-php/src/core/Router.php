<?php
namespace Vendor\VentasPhp\Core;

use Exception;
use Vendor\VentasPhp\Core\Response;

class Router {
    private $routes = [];
    private $notFoundCallback;
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim(str_replace('\\', '/', $basePath), '/');
    }

    public function getRoutes() {
        return $this->routes;
    }

    private function getCurrentUri(): string {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($requestUri, PHP_URL_PATH);
        
        // Normalización completa
        $uri = rawurldecode($uri);
        $uri = str_replace('\\', '/', $uri);
        $uri = '/' . trim($uri, '/');
        
        // Manejo del basePath
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        
        return $uri === '' ? '/' : $uri;
    }

    /*
    private function getCurrentUri(): string {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($requestUri, PHP_URL_PATH);
        $uri = rawurldecode(rtrim($uri ?? '/', '/')) ?: '/';
        
        // Normaliza las barras
        $uri = str_replace('\\', '/', $uri);
        
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath)) ?: '/';
        }
        
        return $uri;
    }
    /** */

    public function add($method, $route, $callback): void {
        $methods = is_string($method) ? explode('|', $method) : (array) $method;
        
        // Normalización definitiva de la ruta
        $route = '/' . trim($route, '/');
        $route = str_replace('//', '/', $route);
        
        // Concatenación con basePath
        if ($this->basePath) {
            $route = rtrim($this->basePath, '/') . $route;
        } else {
            $route = '/' . ltrim($route, '/');
        }
        
        foreach ($methods as $m) {
            $this->routes[$m][$route] = $callback;
        }
    }

    

    public function dispatch(): Response {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $this->getCurrentUri();
    
        error_log("Trying to match: $requestUri");
        error_log("Against routes:");
        foreach ($this->routes[$requestMethod] ?? [] as $route => $_) {
            error_log("- $route");
        }
    
        // Coincidencia exacta primero
        if (isset($this->routes[$requestMethod][$requestUri])) {
            error_log("Exact match found for: $requestUri");
            return $this->executeCallback($this->routes[$requestMethod][$requestUri]);
        }
    
        // Coincidencia de patrones
        foreach ($this->routes[$requestMethod] ?? [] as $route => $callback) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
                error_log("Pattern matched: $route");
                return $this->executeCallback($callback, $params);
            }
        }
    
        // Ruta no encontrada
        error_log("No route found for: $requestUri");
        if ($this->notFoundCallback) {
            return $this->executeCallback($this->notFoundCallback);
        }
        
        return new Response('Página no encontrada', 404);
    }

    public function getBasePath(): string {
        return $this->basePath;
    }
    


    public function setNotFound($callback): void {
        $this->notFoundCallback = $callback;
    }

    private function buildPattern($route): string {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route);
        return '@^' . $pattern . '$@i';
    }

    private function executeCallback($callback, array $params = []): Response {
        if (is_callable($callback)) {
            $result = call_user_func_array($callback, $params);
            return $result instanceof Response ? $result : new Response((string)$result);
        }

        if (is_string($callback)) {
            if (strpos($callback, '@') === false) {
                throw new Exception("Invalid callback string: $callback");
            }

            list($controller, $method) = explode('@', $callback);
            if (!class_exists($controller)) {
                throw new Exception("Controller not found: $controller");
            }

            $controllerInstance = new $controller();
            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Method $method not found in controller $controller");
            }

            $result = $controllerInstance->$method(...$params);
            return $result instanceof Response ? $result : new Response((string)$result);
        }

        throw new Exception('Invalid callback');
    }
}
