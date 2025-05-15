<?php
namespace Vendor\VentasPhp\Models;

use PDO; // Importar la clase PDO global
use Vendor\VentasPhp\Services\DatabaseService; // Asumiendo que tienes un servicio de base de datos

class Report {
    private $db;

    public function __construct() {
        $this->db = DatabaseService::getConnection(); // Método estático para obtener la conexión
    }

    public function getSales(array $filters = []): array {
        $query = "SELECT s.*, u.usuario as user_name, c.nombre as client_name 
        FROM ventas s
        LEFT JOIN usuarios u ON s.idUsuario = u.id
        LEFT JOIN clientes c ON s.idCliente = c.id
        WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['start_date'])) {
            $query .= " AND s.fecha >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $query .= " AND s.fecha <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['user_id'])) {
            $query .= " AND s.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['client_id'])) {
            $query .= " AND s.client_id = ?";
            $params[] = $filters['client_id'];
        }
        
        $query .= " ORDER BY s.fecha DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalesStats(array $sales): array {
        $stats = [
            'total_sales' => 0,
            'total_amount' => 0,
            'average_sale' => 0
        ];
        
        if (!empty($sales)) {
            $stats['total_sales'] = count($sales);
            $stats['total_amount'] = array_sum(array_column($sales, 'total'));
            $stats['average_sale'] = $stats['total_amount'] / $stats['total_sales'];
        }
        
        return $stats;
    }

    public function getAllClients(): array {
        $stmt = $this->db->query("SELECT id, nombre FROM clientes ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers(): array {
        $stmt = $this->db->query("SELECT id, usuario FROM usuarios ORDER BY usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}