<?php
namespace Vendor\VentasPhp\Services;

use PDO;
use PDOException;
use Exception;
use Vendor\VentasPhp\Models\User;
use Vendor\VentasPhp\Config\Database;

class AuthService {
    private $db;
    private $userModel;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->userModel = new User();
    }

    public function authenticate(string $username, string $password): ?object {
        try {
            // Se eliminó la condición 'activo = 1'
            $stmt = $this->db->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user && password_verify($password, $user->password)) {
                unset($user->password); // Eliminar password del objeto
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            error_log('Error en AuthService::authenticate(): ' . $e->getMessage());
            throw new Exception('Error de autenticación. Intente nuevamente.');
        }
    }

    public function getUserById(int $id): ?object {
        try {
            // Se eliminó 'rol' de la consulta SQL
            $stmt = $this->db->prepare("SELECT id, usuario FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en AuthService::getUserById(): ' . $e->getMessage());
            return null;
        }
    }
}
