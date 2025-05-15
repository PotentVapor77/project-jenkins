<?php
namespace Vendor\VentasPhp\Models;
use Vendor\VentasPhp\Config\Database;
use PDOException;

class Sale {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($products, $userId, $clientId, $total) {
        try {
            $this->db->beginTransaction();

            // Registrar venta
            $stmt = $this->db->prepare(
                "INSERT INTO ventas (fecha, total, idUsuario, idCliente) 
                 VALUES (NOW(), ?, ?, ?)"
            );
            $stmt->execute([$total, $userId, $clientId]);
            $saleId = $this->db->lastInsertId();

            // Registrar productos vendidos
            foreach ($products as $product) {
                $stmt = $this->db->prepare(
                    "INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) 
                     VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([
                    $product->cantidad,
                    $product->venta,
                    $product->id,
                    $saleId
                ]);

                // Actualizar existencia
                $this->updateStock($product->id, $product->cantidad);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en venta: " . $e->getMessage());
            return false;
        }
    }

    public static function calculateTotal(array $cartItems): float {
        $total = 0.0;
        
        foreach ($cartItems as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $total += (float)$item['price'] * (int)$item['quantity'];
            }
        }
        
        return $total;
    }

    private function updateStock($productId, $quantity) {
        $stmt = $this->db->prepare(
            "UPDATE productos SET existencia = existencia - ? 
             WHERE id = ?"
        );
        $stmt->execute([$quantity, $productId]);
    }

    public function getTotalVentas() {
        $stmt = $this->db->query("SELECT SUM(total) as total FROM ventas");
        $result = $stmt->fetch();
        return $result->total ?? 0;  // Accediendo con notación de objeto
    }
    
    public function getTotalVentasHoy() {
        $stmt = $this->db->query("SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = CURDATE()");
        $result = $stmt->fetch();
        return $result->total ?? 0;  // Accediendo con notación de objeto
    }
    
    public function getTotalVentasSemana() {
        $stmt = $this->db->query("SELECT SUM(total) as total FROM ventas WHERE YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)");
        $result = $stmt->fetch();
        return $result->total ?? 0;  // Accediendo con notación de objeto
    }
    
    public function getTotalVentasMes() {
        $stmt = $this->db->query("SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())");
        $result = $stmt->fetch();
        return $result->total ?? 0;  // Accediendo con notación de objeto
    }
    
    // En Sale.php
    public static function ventasPorUsuario(): array {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT u.nombre AS usuario, COUNT(v.id) AS total_ventas, SUM(v.total) AS total_monto
            FROM ventas v
            INNER JOIN usuarios u ON v.idUsuario = u.id
            GROUP BY v.idUsuario
        ");
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function count(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM ventas");
        return (int) $stmt->fetchColumn();
    }
    
    public function ventasPorCliente(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT idCliente, COUNT(*) as total FROM ventas GROUP BY idCliente"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
}