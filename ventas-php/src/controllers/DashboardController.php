<?php
namespace Vendor\VentasPhp\Controllers;

use Vendor\VentasPhp\Services\DashboardFunctions;
use Vendor\VentasPhp\Services\SessionService;

class DashboardController {
    public function index() {
        SessionService::checkAuth();

        $cartas = [
            ["titulo" => "Total ventas", "icono" => "fa fa-money-bill", "total" => "$" . DashboardFunctions::obtenerTotalVentas(), "color" => "#A71D45"],
            ["titulo" => "Ventas hoy", "icono" => "fa fa-calendar-day", "total" => "$" . DashboardFunctions::obtenerTotalVentasHoy(), "color" => "#2A8D22"],
            ["titulo" => "Ventas semana", "icono" => "fa fa-calendar-week", "total" => "$" . DashboardFunctions::obtenerTotalVentasSemana(), "color" => "#223D8D"],
            ["titulo" => "Ventas mes", "icono" => "fa fa-calendar-alt", "total" => "$" . DashboardFunctions::obtenerTotalVentasMes(), "color" => "#D55929"],
        ];

        $totales = [
            ["nombre" => "Total productos", "total" => DashboardFunctions::obtenerNumeroProductos(), "imagen" => "/assets/img/productos.png"],
            ["nombre" => "Ventas registradas", "total" => DashboardFunctions::obtenerNumeroVentas(), "imagen" => "img/ventas.png"],
            ["nombre" => "Usuarios registrados", "total" => DashboardFunctions::obtenerNumeroUsuarios(), "imagen" => "/assets/img/usuarios.png"],
            ["nombre" => "Clientes registrados", "total" => DashboardFunctions::obtenerNumeroClientes(), "imagen" => "/assets/img/clientes.png"],
        ];

        $ventasUsuarios = DashboardFunctions::obtenerVentasPorUsuario();
        $ventasClientes = DashboardFunctions::obtenerVentasPorCliente();
        $productosMasVendidos = DashboardFunctions::obtenerProductosMasVendidos();

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
