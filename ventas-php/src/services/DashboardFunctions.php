<?php
namespace Vendor\VentasPhp\Services;

use Vendor\VentasPhp\Models\Sale;
use Vendor\VentasPhp\Models\Product;
use Vendor\VentasPhp\Models\User;
use Vendor\VentasPhp\Models\Client;

class DashboardFunctions {
    public static function obtenerTotalVentas(): float {
        $sale = new Sale();
        return $sale->getTotalVentas();
    }

    public static function obtenerTotalVentasHoy(): float {
        $sale = new Sale();
        return $sale->getTotalVentasHoy();
    }

    public static function obtenerTotalVentasSemana(): float {
        $sale = new Sale();
        return $sale->getTotalVentasSemana();
    }

    public static function obtenerTotalVentasMes(): float {
        $sale = new Sale();
        return $sale->getTotalVentasMes();
    }

    public static function obtenerNumeroProductos(): int {
        $product = new Product();
        return $product->count();
    }

    public static function obtenerNumeroVentas(): int {
        $sale = new Sale();
        return $sale->count();
    }

    public static function obtenerNumeroUsuarios(): int {
        $user = new User();
        return $user->count();
    }

    public static function obtenerNumeroClientes(): int {
        $client = new Client();
        return $client->count();
    }

    public static function obtenerVentasPorUsuario(): array {
        $sale = new Sale();
        return $sale->ventasPorUsuario();
    }

    public static function obtenerVentasPorCliente(): array {
        $sale = new Sale();
        return $sale->ventasPorCliente();
    }

    public static function obtenerProductosMasVendidos(): array {
        $product = new Product();
        return $product->getTopVendidos(10);
    }
}
