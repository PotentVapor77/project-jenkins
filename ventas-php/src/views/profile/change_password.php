<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<div class="container">
    <h1>Cambiar contraseña</h1>
    
    <?php include __DIR__.'/../shared/flash.php'; ?>
    
    <form method="post">
        <div class="mb-3">
            <label for="actual" class="form-label">Contraseña actual</label>
            <input type="password" name="actual" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nueva" class="form-label">Contraseña nueva</label>
            <input type="password" name="nueva" class="form-control" required minlength="8">
        </div>

        <div class="mb-3">
            <label for="repite" class="form-label">Repetir contraseña</label>
            <input type="password" name="repite" class="form-control" required minlength="8">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Cambiar contraseña</button>
            <a href="/profile" class="btn btn-danger btn-lg">Cancelar</a>
        </div>
    </form>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>