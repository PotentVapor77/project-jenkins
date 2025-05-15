<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\Sale;
use Vendor\VentasPhp\Models\Client;
use Vendor\VentasPhp\Services\SessionService;

class SaleController {
    private $saleModel;
    private $clientModel;

    public function __construct() {
        $this->saleModel = new Sale();
        $this->clientModel = new Client();
        SessionService::checkAuth();
    }

    public function create() {
        $cart = SessionService::getCart();
        $total = $this->saleModel->calculateTotal($cart);
        $clients = $this->clientModel->getAll();
        $selectedClient = SessionService::getSelectedClient();

        require_once __DIR__.'/../views/sales/create.php';
    }

    public function setClient() {
        $clientId = $_POST['idCliente'] ?? null;
        SessionService::setSelectedClient($clientId);
        header("Location: /sales");
        exit();
    }

    public function cancel() {
        SessionService::clearCart();
        SessionService::clearSelectedClient();
        header("Location: /sales");
        exit();
    }
}