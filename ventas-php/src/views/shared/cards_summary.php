<?php
use Vendor\VentasPhp\Services\DashboardFunctions;

$cartas = [
    ["titulo" => "Total ventas", "icono" => "fa fa-money-bill", "total" => "$".DashboardFunctions::obtenerTotalVentas(), "color" => "#A71D45"],
    ["titulo" => "Ventas hoy", "icono" => "fa fa-calendar-day", "total" => "$".DashboardFunctions::obtenerTotalVentasHoy(), "color" => "#2A8D22"],
    ["titulo" => "Ventas semana", "icono" => "fa fa-calendar-week", "total" => "$".DashboardFunctions::obtenerTotalVentasSemana(), "color" => "#223D8D"],
    ["titulo" => "Ventas mes", "icono" => "fa fa-calendar-alt", "total" => "$".DashboardFunctions::obtenerTotalVentasMes(), "color" => "#D55929"],
];
?>

<div class="card-deck row mb-2">
<?php foreach($cartas as $carta){?>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="card text-white text-center mb-3" style="background-color: <?= $carta['color']?>;">
            <div class="card-body">
                <i class="<?= $carta['icono']?> fa-2x"></i>
                <h5 class="card-title"><?= $carta['titulo']?></h5>
                <h2 class="card-text"><?= $carta['total']?></h2>
            </div>
        </div>
    </div>
<?php }?>
</div>
