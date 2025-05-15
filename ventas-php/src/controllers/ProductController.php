<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\Product;
use Vendor\VentasPhp\Services\SessionService;
use Vendor\VentasPhp\Core\AuthMiddleware;
use Exception;
use PDOException;

class ProductController {
    private $productModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->productModel = new Product();
    }

    private function verifyAuthentication(): void {
        if (!SessionService::isAuthenticated()) {
            SessionService::setFlash('error', 'Authentication required');
            header("Location: /login");
            exit();
        }
    }

    public function index(): void {
        try {
            $searchTerm = $_POST['searchTerm'] ?? null;
            $products = $this->productModel->getAll($searchTerm);
            $stats = [
                'total_products' => $this->productModel->getTotalCount(),
                'inventory_value' => $this->productModel->getInventoryValue()
            ];
            
            require_once __DIR__.'/../views/products/index.php';
        } catch (PDOException $e) {
            error_log('ProductController::index() error: ' . $e->getMessage());
            SessionService::setFlash('error', 'Error loading products');
            header("Location: /");
        }
    }

    public function create(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method', 405);
            }

            $productData = [
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'compra' => (float)$_POST['compra'],
                'venta' => (float)$_POST['venta'],
                'existencia' => (int)$_POST['existencia']
            ];

            $result = $this->productModel->insert($productData);
            
            if ($result) {
                SessionService::setFlash('success', 'Product created successfully');
                header("Location: /products");
            } else {
                throw new Exception('Failed to create product');
            }
        } catch (Exception $e) {
            error_log('ProductController::create() error: ' . $e->getMessage());
            SessionService::setFlash('error', $e->getMessage());
            header("Location: /products/create");
        }
    }

    public function delete($id) {
        if ($this->productModel->delete($id)) {
            SessionService::setFlash('success', 'Producto eliminado');
        } else {
            SessionService::setFlash('error', 'Error al eliminar producto');
        }
        header("Location: /products");
        exit();
    }

    public function createForm(): void {
        require_once __DIR__.'/../views/products/create.php';
    }

    // ... métodos para edit, delete, etc
}