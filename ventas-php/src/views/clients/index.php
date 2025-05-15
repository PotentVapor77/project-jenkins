<?php

use Vendor\VentasPhp\Models\Client;
use Vendor\VentasPhp\Services\SessionService;
SessionService::requireAuth();

include_once __DIR__ . '/../shared/header.php';
include_once __DIR__ . '/../shared/navbar.php';
?>

<div class="container">
    <h3>Agregar cliente</h3>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Escribe el nombre del cliente">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Ej. 2111568974">
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Ej. Av Collar 1005 Col Las Cruces">
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            <a href="/clientes" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </form>

    <?php
    if (isset($_POST['registrar'])) {
        require_once __DIR__ . '/../../models/Client.php';

        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($telefono) || empty($direccion)) {
            echo '<div class="alert alert-danger mt-3">Debes completar todos los datos.</div>';
        } else {
            $client = new Client();
            $success = $client->create([
                'nombre' => $nombre,
                'telefono' => $telefono,
                'direccion' => $direccion,
            ]);

            if ($success) {
                echo '<div class="alert alert-success mt-3">Cliente registrado con éxito.</div>';
            } else {
                echo '<div class="alert alert-danger mt-3">Error al registrar el cliente.</div>';
            }
        }
    }
    ?>
</div>
