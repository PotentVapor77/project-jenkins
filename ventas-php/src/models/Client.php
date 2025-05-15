<?php
namespace Vendor\VentasPhp\Models;

use PDO;
use PDOException;
use Vendor\VentasPhp\Services\DatabaseService;

class Client {
    private PDO $db;

    public function __construct() {
        $this->db = DatabaseService::getConnection();
    }

    public function getAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM clientes");
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en Client::getAll(): ' . $e->getMessage());
            return [];
        }
    }

    public function count(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM clientes");
        return (int) $stmt->fetchColumn();
    }
    

    public function getById(int $id): ?object {
        try {
            $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            error_log('Error en Client::getById(): ' . $e->getMessage());
            return null;
        }
    }

    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?, ?, ?)"
            );
            return $stmt->execute([
                $data['nombre'],
                $data['telefono'],
                $data['direccion']
            ]);
        } catch (PDOException $e) {
            error_log('Error en Client::create(): ' . $e->getMessage());
            return false;
        }
    }

    public function update(array $data): bool {
        try {
            $stmt = $this->db->prepare(
                "UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE id = ?"
            );
            return $stmt->execute([
                $data['nombre'],
                $data['telefono'],
                $data['direccion'],
                $data['id']
            ]);
        } catch (PDOException $e) {
            error_log('Error en Client::update(): ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Error en Client::delete(): ' . $e->getMessage());
            return false;
        }
    }

    public function search(string $term): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM clientes WHERE nombre LIKE ?");
            $stmt->execute(["%$term%"]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en Client::search(): ' . $e->getMessage());
            return [];
        }
    }

    public function getTotalCount(): int {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM clientes");
            return (int)$stmt->fetch()->total;
        } catch (PDOException $e) {
            error_log('Error en Client::getTotalCount(): ' . $e->getMessage());
            return 0;
        }
    }

    public function getPaginated(int $page = 1, int $perPage = 10): array {
        try {
            $offset = ($page - 1) * $perPage;
            $stmt = $this->db->prepare("SELECT * FROM clientes LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Error en Client::getPaginated(): ' . $e->getMessage());
            return [];
        }
    }
}