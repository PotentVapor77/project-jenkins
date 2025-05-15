<?php

namespace Vendor\VentasPhp\Models;

use PDO;
use PDOException;
use Vendor\VentasPhp\Services\DatabaseService;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DatabaseService::getConnection();
    }

    public function authenticate(string $username, string $password): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Elimina la clave 'password' antes de retornar
                unset($user['password']);
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            error_log('Error en User::authenticate(): ' . $e->getMessage());
            return null;
        }
    }

    public function getAll(): array
    {
        try {
            return $this->db
                ->query("SELECT id, usuario, nombre, telefono, direccion FROM usuarios")
                ->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en User::getAll(): ' . $e->getMessage());
            return [];
        }
    }

    public function getById(int $id): ?object // Devolver un objeto
    {
        try {
            $stmt = $this->db->prepare("SELECT id, usuario, nombre, telefono, direccion, created_at FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null; 
        } catch (PDOException $e) {
            error_log('Error en User::getById(): ' . $e->getMessage());
            return null;
        }
    }



    public function create(array $data): bool
    {
        try {
            $password = password_hash($data['password'] ?? 'default123', PASSWORD_DEFAULT);
            $stmt = $this->db->prepare(
                "INSERT INTO usuarios (usuario, nombre, telefono, direccion, password) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            return $stmt->execute([
                $data['usuario'],
                $data['nombre'],
                $data['telefono'],
                $data['direccion'],
                $password
            ]);
        } catch (PDOException $e) {
            error_log('Error en User::create(): ' . $e->getMessage());
            return false;
        }
    }

    public function findByUsername(string $username): ?object
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            error_log('Error en User::findByUsername(): ' . $e->getMessage());
            return null;
        }
    }

    public function verifyCurrentPassword(int $userId, string $password): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT password FROM usuarios WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user && password_verify($password, $user['password']);
        } catch (PDOException $e) {
            error_log('Error en User::verifyCurrentPassword(): ' . $e->getMessage());
            return false;
        }
    }

    public function updateUserPassword(int $userId, string $newPassword): bool
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            error_log('Error en User::updateUserPassword(): ' . $e->getMessage());
            return false;
        }
    }

    public function getSalesCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM ventas WHERE user_id = ?");
            $stmt->execute([$userId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en User::getSalesCount(): ' . $e->getMessage());
            return 0;
        }
    }

    public function getTodaySales(int $userId): float
    {
        try {
            $stmt = $this->db->prepare("SELECT SUM(total) FROM ventas 
                                      WHERE user_id = ? AND DATE(fecha) = CURDATE()");
            $stmt->execute([$userId]);
            return (float)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en User::getTodaySales(): ' . $e->getMessage());
            return 0.0;
        }
    }

    public function update(array $userData): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE usuarios SET 
                    usuario = :usuario,
                    nombre = :nombre,
                    telefono = :telefono,
                    direccion = :direccion
                 WHERE id = :id"
            );

            return $stmt->execute([
                ':id' => $userData['id'],
                ':usuario' => $userData['usuario'],
                ':nombre' => $userData['nombre'],
                ':telefono' => $userData['telefono'],
                ':direccion' => $userData['direccion']
            ]);
        } catch (PDOException $e) {
            error_log('Error en User::update(): ' . $e->getMessage());
            return false;
        }
    }

    public function getPaginated(int $page = 1, int $perPage = 10): array
    {
        try {
            $offset = ($page - 1) * $perPage;
            $stmt = $this->db->prepare("SELECT id, usuario, nombre, telefono, direccion FROM usuarios LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en User::getPaginated(): ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalCount(): int
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM usuarios");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($result['total']) ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log('Error en User::getTotalCount(): ' . $e->getMessage());
            return 0;
        }
    }

    // Este método puede eliminarse si ya no usas el campo rol
    public function updateRole(int $userId, string $role): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
            return $stmt->execute([$role, $userId]);
        } catch (PDOException $e) {
            error_log('Error en User::updateRole(): ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET activo = 0 WHERE id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log('Error en User::delete(): ' . $e->getMessage());
            return false;
        }
    }

    

    public function search(string $term): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, usuario, nombre, telefono, direccion 
                 FROM usuarios 
                 WHERE usuario LIKE ? OR nombre LIKE ?
                 LIMIT 20"
            );
            $searchTerm = "%$term%";
            $stmt->execute([$searchTerm, $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en User::search(): ' . $e->getMessage());
            return [];
        }
    }

    public function count(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM usuarios");
        return (int) $stmt->fetchColumn();
    }
    
    // Eliminamos `rol` de la consulta si ya no lo usas
    public function findById(int $id): ?object
    {
        try {
            $stmt = $this->db->prepare("SELECT id, usuario, nombre, telefono, direccion FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            error_log('Error en User::findById(): ' . $e->getMessage());
            return null;
        }
    }
}
