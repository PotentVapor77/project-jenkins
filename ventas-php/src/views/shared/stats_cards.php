<?php
use Vendor\VentasPhp\Services\DashboardFunctions;

$totales = [
    ["nombre" => "Total productos", "total" => DashboardFunctions::obtenerNumeroProductos(), "imagen" => "/assets/img/productos.png"],
    ["nombre" => "Ventas registradas", "total" => DashboardFunctions::obtenerNumeroVentas(), "imagen" => "/assets/img/ventas.png"],
    ["nombre" => "Usuarios registrados", "total" => DashboardFunctions::obtenerNumeroUsuarios(), "imagen" => "/assets/img/usuarios.png"],
    ["nombre" => "Clientes registrados", "total" => DashboardFunctions::obtenerNumeroClientes(), "imagen" => "/assets/img/clientes.png"],
];
?>

<div class="card-deck row mb-2">
<?php foreach($totales as $total){?>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <img class="img-thumbnail" src="<?= $total['imagen']?>" alt="">
                <h4 class="card-title"><?= $total['nombre']?></h4>
                <h2><?= $total['total']?></h2>
            </div>
        </div>
    </div>
<?php }?>
</div>
