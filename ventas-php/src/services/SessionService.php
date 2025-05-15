<?php
namespace Vendor\VentasPhp\Services;
class SessionService {
    /**
     * Inicia la sesión con configuraciones seguras
     */
    
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'use_strict_mode' => true,
                'use_cookies' => true,
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_samesite' => 'Lax',
                'gc_maxlifetime' => 1800 // 30 minutos
            ]);
        }
    }

    public static function checkAuth(): void {
        self::start();
        if (!isset($_SESSION['user'])) {
            self::addFlash('error', 'Debes iniciar sesión para acceder a esta página');
            header('Location: /login');
            exit();
        }
    }

    public static function requireAuth(): void {
        if (!self::isAuthenticated()) {
            self::setFlash('error', 'Debes iniciar sesión');
            header('Location: /login');
            exit;
        }
    }

    private static function regenerateId(): void {
        self::start();
        session_regenerate_id(true);
    }

    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function getUser(): ?array {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function clearCart(): void {
        self::remove('cart');
        self::remove('selected_client');
    }

    public static function addFlash(string $type, string $message): void {
        self::start();
        $_SESSION['flash'][$type] = $message;
    }

    public static function getCart(): array {
        self::start();
        return $_SESSION['cart'] ?? [];
    }

    public static function setCart(array $cart): void {
        self::start();
        $_SESSION['cart'] = $cart;
    }

    /**
     * Métodos para manejar el cliente seleccionado
     */
    public static function getSelectedClient(): ?array {
        self::start();
        return $_SESSION['selected_client'] ?? null;
    }

    public static function setSelectedClient(array $client): void {
        self::start();
        $_SESSION['selected_client'] = $client;
    }

    public static function clearSelectedClient(): void {
        self::remove('selected_client');
    }

     /**
     * Verifica si existe un mensaje flash
     */
    public static function hasFlash(): bool {
        self::start();
        return isset($_SESSION['flash']);
    }

    /**
     * Obtiene y elimina un mensaje flash
     */
    public static function getFlash(): ?array {
        self::start();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Establece un mensaje flash
     */
    public static function setFlash(string $type, string $message): void {
        self::start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }


    public static function isAuthenticated(): bool {
        self::start();
        return isset($_SESSION['user']);
    }
    
    public static function login(array $userData): void {
        self::start();
        $_SESSION['user'] = $userData;
        self::regenerateId();
    }
    
    public static function logout(): void {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }


}