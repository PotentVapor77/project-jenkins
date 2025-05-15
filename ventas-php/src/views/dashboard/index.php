<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<?php
use Vendor\VentasPhp\Services\SessionService;

SessionService::start();

// Protegemos la página
if (!SessionService::isAuthenticated()) {
    header('Location: /login');
    exit;
}

// Obtenemos el nombre
$nombreUsuario = $_SESSION['nombre'] ?? $_SESSION['usuario'] ?? 'Usuario';
?>


<div class="container">
    <div class="alert alert-info" role="alert">
            <h1>Hola, <?= isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'usuario' ?></h1>
    </div>

    <?php include __DIR__.'/../shared/cards_summary.php'; ?>
    <?php include __DIR__.'/../shared/stats_cards.php'; ?>

    <?php include __DIR__.'/../shared/sales_by_user_table.php'; ?>
    <?php include __DIR__.'/../shared/sales_by_client_table.php'; ?>
    <?php include __DIR__.'/../shared/top_products_table.php'; ?>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>
