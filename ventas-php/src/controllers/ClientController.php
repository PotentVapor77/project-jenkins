<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\Client;
use Vendor\VentasPhp\Services\SessionService;
use PDOException;
use Exception;

class ClientController {
    private $clientModel;


    public function __construct() {
        $this->clientModel = new Client();
        $this->checkAuthentication();
    }

    public function index(): void {
        try {
            $clients = $this->clientModel->getAll();
            error_log("ClientController@index ejecutado");
            require_once __DIR__.'/../views/clients/index.php';
        } catch (PDOException $e) {
            error_log('Error en ClientController::index(): ' . $e->getMessage());
            SessionService::setFlash('error', 'Error al cargar clientes');
            header("Location: /");
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion']
            ];
            
            if ($this->clientModel->create($data)) {
                SessionService::setFlash('success', 'Cliente registrado con éxito');
                header("Location: /clients");
                exit();
            }
        }
        require_once __DIR__.'/../views/clients/create.php';
    }

    public function edit(int $id): void {
        try {
            $client = $this->clientModel->getById($id);
            if (!$client) {
                throw new Exception('Cliente no encontrado');
            }
            require_once __DIR__.'/../views/clients/edit.php';
        } catch (Exception $e) {
            error_log('Error en ClientController::edit(): ' . $e->getMessage());
            SessionService::setFlash('error', $e->getMessage());
            header("Location: /clients");
        }
    }

    public function delete($id) {
        if ($this->clientModel->delete($id)) {
            SessionService::setFlash('success', 'Cliente eliminado');
        } else {
            SessionService::setFlash('error', 'Error al eliminar');
        }
        header("Location: /clients");
    }

    public function cancelSale(): void {
        try {
            SessionService::remove('cart');
            SessionService::remove('selected_client');
            SessionService::setFlash('info', 'Venta cancelada correctamente');
            header("Location: /sales");
        } catch (Exception $e) {
            error_log('Error en ClientController::cancelSale(): ' . $e->getMessage());
            SessionService::setFlash('error', 'Error al cancelar venta');
            header("Location: /sales");
        }
    }

    private function checkAuthentication(): void {
        if (!SessionService::isAuthenticated()) {
            SessionService::setFlash('error', 'Debes iniciar sesión');
            header("Location: /login");
            exit();
        }
    }
    
}