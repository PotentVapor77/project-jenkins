<?php
use Vendor\VentasPhp\Services\DashboardFunctions;

$productosMasVendidos = DashboardFunctions::obtenerProductosMasVendidos();
?>

<div class="card mt-4">
    <div class="card-body">
        <h4 class="mt-4">10 Productos más vendidos</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Unidades vendidas</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productosMasVendidos as $producto) {?>
                <tr>
                    <td><?= htmlspecialchars($producto->nombre ?? 'Producto desconocido') ?></td>
                    <td ><?= number_format($producto->unidades ?? 0) ?></td>
                    <td >$<?= number_format($producto->total ?? 0, 2) ?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>