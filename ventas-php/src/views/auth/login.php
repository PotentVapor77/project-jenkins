<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="/assets/img/logoS.png" alt="Logo" width="120" class="mb-3">
                <h2 class="card-title">Iniciar Sesión</h2>
            </div>

            <?php include __DIR__ . '/../shared/flash.php'; ?>

            <form action="/login" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shared/footer.php'; ?>
