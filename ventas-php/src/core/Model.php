<?php
namespace Vendor\VentasPhp\Core;
use Vendor\VentasPhp\Config\Database;
abstract class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}