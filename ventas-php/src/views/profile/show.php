<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<div class="container mt-5">
    <h1>Perfil de Usuario</h1>

    <!-- Mostrar mensajes flash -->
    <?php include __DIR__.'/../shared/flash.php'; ?>

    <!-- Mostrar nombre del usuario desde la sesión -->
    <h1>Hola, <?= isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Invitado' ?></h1>

    <?php if (isset($_SESSION['usuario'])): ?>
        <!-- Información del usuario -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($_SESSION['usuario']) ?></h3>
                <!-- Si quieres incluir más detalles del usuario, puedes agregar otros campos de la sesión -->
                <p class="card-text">Correo electrónico: <?= htmlspecialchars($_SESSION['usuario']) ?></p>
            </div>
        </div>

        <!-- Estadísticas de ventas -->
        <div class="mt-4">
            <h3>Estadísticas de ventas</h3>
            <ul>
                <li>Total de ventas: <?= $stats['total_sales']; ?></li>
                <li>Ventas hoy: <?= $stats['today_sales']; ?></li>
            </ul>
        </div>

        <!-- Enlaces para cambiar contraseña o editar perfil -->
        <div class="mt-4">
            <a href="/profile/change-password" class="btn btn-warning">Cambiar Contraseña</a>
            <a href="/profile/edit" class="btn btn-primary">Editar Perfil</a>
        </div>

    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            No se pudo cargar la información del perfil. Intenta nuevamente.
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>
