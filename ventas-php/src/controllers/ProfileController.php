<?php

namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\User;
use Vendor\VentasPhp\Services\SessionService;
use Exception;

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->verifyAuthentication();
    }

    private function verifyAuthentication(): void
    {
        if (!SessionService::isAuthenticated()) {
            SessionService::addFlash('error', 'You must be logged in');
            header("Location: /login");
            exit();
        }
    }

    public function changePassword(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }

            $userId = SessionService::get('user')['id'] ?? null;
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validaciones
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                throw new Exception('Todos los campos son requeridos');
            }

            if ($newPassword !== $confirmPassword) {
                throw new Exception('Las contraseñas no coinciden');
            }

            if (strlen($newPassword) < 8) {
                throw new Exception('La contraseña debe tener al menos 8 caracteres');
            }

            // Verificar contraseña actual
            if (!$this->userModel->verifyCurrentPassword($userId, $currentPassword)) {
                throw new Exception('La contraseña actual es incorrecta');
            }

            // Actualizar contraseña
            if ($this->userModel->updateUserPassword($userId, $newPassword)) {
                SessionService::addFlash('success', 'Contraseña actualizada exitosamente');
                header("Location: /profile");
                exit();
            }

            throw new Exception('Error al actualizar la contraseña');
        } catch (Exception $e) {
            error_log('Error en ProfileController::changePassword(): ' . $e->getMessage());
            SessionService::addFlash('error', $e->getMessage());
            header("Location: /profile/change-password");
        }
    }

    public function show(): void
    {
        try {
            // Obtener el ID del usuario desde la sesión
            $userId = SessionService::get('user')['id'] ?? null;

            if (!$userId) {
                throw new Exception('Usuario no autenticado');
            }

            // Obtener el objeto usuario del modelo
            $user = $this->userModel->findById($userId);

            // Verificar que el usuario es un objeto válido
            if (!$user || !is_object($user)) {
                throw new Exception('Usuario no encontrado');
            }

            // Asignar el nombre del usuario a la sesión
            $_SESSION['usuario'] = $user->nombre;

            // Obtener las estadísticas
            $stats = [
                'total_sales' => $this->userModel->getSalesCount($userId),
                'today_sales' => $this->userModel->getTodaySales($userId)
            ];

            // Incluir la vista
            require_once __DIR__ . '/../views/profile/show.php';
        } catch (Exception $e) {
            error_log('ProfileController error: ' . $e->getMessage());
            SessionService::addFlash('error', 'Error al cargar el perfil');
            header("Location: /dashboard");
        }
    }
}
