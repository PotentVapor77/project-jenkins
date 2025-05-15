<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\User;
use Vendor\VentasPhp\Services\SessionService;
use Vendor\VentasPhp\Services\AuthService;
use Exception;

class AuthController {
    private $authService;
    private $userModel;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userModel = new User();
        SessionService::start(); 
    }

    public function showLogin() {
        if (SessionService::isAuthenticated()) {
            header("Location: /dashboard");
            exit();
        }

        $this->render('auth/login');
    }

    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }

            $username = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                throw new Exception('Debes completar todos los campos', 400);
            }

            $user = $this->authService->authenticate($username, $password);

            if (!is_object($user)) {
                throw new Exception('Credenciales incorrectas', 401);
            }

            SessionService::login([
                'id' => $user->id,
                'usuario' => $user->usuario,
                'nombre' => $user->nombre ?? null,
            ]);

            header("Location: /dashboard");
            exit();

        } catch (Exception $e) {
            error_log('Error en login: ' . $e->getMessage());
            SessionService::setFlash('error', $e->getMessage());
            header("Location: /login");
            exit();
        }
    }

    public function logout() {
        try {
            SessionService::logout();
            SessionService::setFlash('info', 'Has cerrado sesión correctamente');
            header("Location: /login");
            exit();
        } catch (Exception $e) {
            error_log('Error en logout: ' . $e->getMessage());
            header("Location: /login");
            exit();
        }
    }
    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }
}
