<?php
namespace Vendor\VentasPhp\Controllers;
use Vendor\VentasPhp\Models\User;
use Vendor\VentasPhp\Services\SessionService;
use Vendor\VentasPhp\Core\AuthMiddleware;
use Exception;

class UserController {
    private $userModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->userModel = new User();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $users = $this->userModel->getPaginated($page, $perPage);
        $totalUsers = $this->userModel->getTotalCount();
        $totalPages = ceil($totalUsers / $perPage);
        
        require_once __DIR__.'/../views/users/index.php';
    }

    public function showCreate() {
        require_once __DIR__.'/../views/users/create.php';
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }

            $data = [
                'usuario' => $_POST['usuario'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if (empty($data['usuario'])) {
                throw new Exception('El nombre de usuario es requerido');
            }

            if ($this->userModel->findByUsername($data['usuario'])) {
                throw new Exception('El nombre de usuario ya existe');
            }

            if ($this->userModel->create($data)) {
                SessionService::setFlash('success', 'Usuario creado exitosamente');
                header("Location: /users");
                exit();
            }

            throw new Exception('Error al crear el usuario');
            
        } catch (Exception $e) {
            SessionService::setFlash('error', $e->getMessage());
            header("Location: /users/create");
            exit();
        }
    }

    public function showEdit($params) {
        $userId = $params['id'] ?? null;
        if (!$userId) {
            SessionService::setFlash('error', 'ID de usuario no proporcionado');
            header("Location: /users");
            exit();
        }

        $user = $this->userModel->getById($userId);
        if (!$user) {
            SessionService::setFlash('error', 'Usuario no encontrado');
            header("Location: /users");
            exit();
        }

        require_once __DIR__.'/../views/users/edit.php';
    }

    public function edit($params) {
        try {
            $userId = $params['id'] ?? null;
            if (!$userId) {
                throw new Exception('ID de usuario no proporcionado');
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }

            $data = [
                'id' => $userId,
                'usuario' => $_POST['usuario'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if (empty($data['usuario'])) {
                throw new Exception('El nombre de usuario es requerido');
            }

            if ($this->userModel->update($data)) {
                SessionService::setFlash('success', 'Usuario actualizado exitosamente');
                header("Location: /users");
                exit();
            }

            throw new Exception('Error al actualizar el usuario');
            
        } catch (Exception $e) {
            SessionService::setFlash('error', $e->getMessage());
            header("Location: /users/edit/".($params['id'] ?? ''));
            exit();
        }
    }

    public function delete($params) {
        try {
            $userId = $params['id'] ?? null;
            if (!$userId) {
                throw new Exception('ID de usuario no proporcionado');
            }

            $currentUser = SessionService::get('user');
            if ($currentUser['id'] == $userId) {
                throw new Exception('No puedes eliminar tu propio usuario');
            }

            $user = $this->userModel->getById($userId);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }

            if ($this->userModel->delete($userId)) {
                SessionService::setFlash('success', 'Usuario eliminado exitosamente');
            } else {
                throw new Exception('Error al eliminar el usuario');
            }
            
        } catch (Exception $e) {
            SessionService::setFlash('error', $e->getMessage());
        }
        
        header("Location: /users");
        exit();
    }

    public function updateRole($params) {
        try {
            $userId = $params['id'] ?? null;
            $role = $_POST['role'] ?? null;

            if (!$userId || !$role) {
                throw new Exception('Datos incompletos');
            }

            if ($this->userModel->updateRole($userId, $role)) {
                SessionService::setFlash('success', 'Rol actualizado exitosamente');
            } else {
                throw new Exception('Error al actualizar el rol');
            }
            
        } catch (Exception $e) {
            SessionService::setFlash('error', $e->getMessage());
        }
        
        header("Location: /users");
        exit();
    }
}