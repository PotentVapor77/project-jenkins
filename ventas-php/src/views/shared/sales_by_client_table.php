<?php
use Vendor\VentasPhp\Services\DashboardFunctions;

$ventasClientes = DashboardFunctions::obtenerVentasPorCliente();
?>

<div class="card mt-4">
    <div class="card-body">
        <h4>Ventas por clientes</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre cliente</th>
                    <th>Número compras</th>
                    <th>Total ventas</th>
                </tr>
            </thead>
           <tbody>
                <?php if(empty($ventasClientes)): ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay datos de ventas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($ventasClientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente->cliente ?? 'Cliente desconocido') ?></td>
                            <td><?= $cliente->numeroCompras ?? 0 ?></td>
                            <td>$<?= number_format($cliente->total ?? 0, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
