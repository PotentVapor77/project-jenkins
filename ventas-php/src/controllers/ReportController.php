<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Models\Report;
use Vendor\VentasPhp\Services\SessionService;
use Exception;

class ReportController {
    private $reportModel;

    public function __construct() {
        $this->reportModel = new Report();
        SessionService::checkAuth();
    }

    public function sales() {
        try {
            $filters = [
                'start_date' => $_POST['inicio'] ?? null,
                'end_date' => $_POST['fin'] ?? null,
                'user_id' => $_POST['idUsuario'] ?? null,
                'client_id' => $_POST['idCliente'] ?? null
            ];

            // Asegúrate que getSales() retorne un valor
            $sales = $this->reportModel->getSales($filters);
            
            // Verifica que getSalesStats() también retorne un valor
            $stats = $this->reportModel->getSalesStats($sales);
            
            $clients = $this->reportModel->getAllClients();
            $users = $this->reportModel->getAllUsers();

            require_once __DIR__.'/../views/reports/sales.php';
        } catch (Exception $e) {
            error_log('Error en ReportController: ' . $e->getMessage());
            SessionService::addFlash('error', 'Error al generar el reporte');
            header('Location: /dashboard');
            exit(); // Añadido para asegurar que el script termine
        }
    }
}