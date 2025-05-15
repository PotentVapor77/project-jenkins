<?php
namespace Vendor\VentasPhp\Core;
use Vendor\VentasPhp\Services\SessionService;

class AuthMiddleware {
    public static function handle() {
        SessionService::start();
        
        if (!SessionService::isAuthenticated()) {
            SessionService::setFlash('error', 'Debes iniciar sesión para acceder a esta página');
            header("Location: /login");
            exit();
        }

        // Ya no se requiere verificación de rol
        $user = SessionService::get('user');
        if (!$user || !isset($user['id'])) {
            SessionService::logout();
            SessionService::setFlash('error', 'Sesión inválida');
            header("Location: /login");
            exit();
        }
    }
}
