<?php
namespace Vendor\VentasPhp\Models;
use Vendor\VentasPhp\Config\Database;
use PDO;
use PDOException;


class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(?string $searchTerm = null): array {
        $sql = "SELECT * FROM productos";
        $params = [];
        
        if ($searchTerm) {
            $sql .= " WHERE nombre LIKE ? OR codigo LIKE ?";
            $params = ["%$searchTerm%", "%$searchTerm%"];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getTotalProfit() {
        $stmt = $this->db->query(
            "SELECT IFNULL(SUM(existencia*venta) - SUM(existencia*compra),0) AS total 
             FROM productos"
        );
        return $stmt->fetch()->total;
    }

    public function insert(array $productData): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO productos (codigo, nombre, compra, venta, existencia) 
             VALUES (:codigo, :nombre, :compra, :venta, :existencia)"
        );
        
        return $stmt->execute([
            ':codigo' => $productData['codigo'],
            ':nombre' => $productData['nombre'],
            ':compra' => $productData['compra'],
            ':venta' => $productData['venta'],
            ':existencia' => $productData['existencia']
        ]);
    }

    /**
     * Get total product count
     */
    public function getTotalCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM productos");
        return (int)$stmt->fetch()->total;
    }

    /**
     * Calculate total inventory value
     */
    public function getInventoryValue(): float {
        $stmt = $this->db->query("SELECT SUM(venta * existencia) as total FROM productos");
        return (float)$stmt->fetch()->total;
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        }
    }

    public function count(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM productos");
        return (int) $stmt->fetchColumn();
    }
    

    /**
     * Get product by ID
     */
    public function getById(int $id): ?object {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    /**
     * Update product
     */
    public function update(array $productData): bool {
        $stmt = $this->db->prepare(
            "UPDATE productos SET 
                codigo = :codigo, 
                nombre = :nombre, 
                compra = :compra, 
                venta = :venta, 
                existencia = :existencia 
             WHERE id = :id"
        );
        
        return $stmt->execute([
            ':id' => $productData['id'],
            ':codigo' => $productData['codigo'],
            ':nombre' => $productData['nombre'],
            ':compra' => $productData['compra'],
            ':venta' => $productData['venta'],
            ':existencia' => $productData['existencia']
        ]);
    }

    /**
     * Get products with low stock (below specified threshold)
     */
    public function getLowStock(int $threshold = 5): array {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE existencia < ?");
        $stmt->execute([$threshold]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Search products by name or code
     */
    public function search(string $term): array {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR codigo LIKE ?");
        $stmt->execute(["%$term%", "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTopVendidos($limit = 10): array {
        $stmt = $this->db->prepare("
            SELECT 
                p.id, 
                p.nombre, 
                p.codigo,
                SUM(pv.cantidad) AS total_vendido 
            FROM 
                productos_ventas pv
            INNER JOIN 
                productos p ON pv.idProducto = p.id
            GROUP BY 
                pv.idProducto
            ORDER BY 
                total_vendido DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    
}