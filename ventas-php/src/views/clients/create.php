<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<div class="container">
    <h3>Agregar cliente</h3>
    
    <?php include __DIR__.'/../shared/flash.php'; ?>
    
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" required>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Registrar</button>
            <a href="/clients" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>