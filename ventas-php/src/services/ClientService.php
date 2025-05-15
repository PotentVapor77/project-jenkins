<?php
namespace Vendor\VentasPhp\Services;
use Vendor\VentasPhp\Config\Database;

class ClientService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getClient($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllClients() {
        return $this->db->query("SELECT * FROM clientes")->fetchAll();
    }

    public function registerClient($nombre, $telefono, $direccion) {
        $stmt = $this->db->prepare(
            "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$nombre, $telefono, $direccion]);
    }
}