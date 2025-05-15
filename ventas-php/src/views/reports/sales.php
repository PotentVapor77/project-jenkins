<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>
<?php include __DIR__.'/../shared/cards_summary.php'; ?>

<div class="container">
    <h2>Reporte de ventas</h2>
    
    <form method="post" class="row mb-3">
        <div class="col-md-5">
            <label>Fecha inicial</label>
            <input type="date" name="inicio" class="form-control">
        </div>
        <div class="col-md-5">
            <label>Fecha final</label>
            <input type="date" name="fin" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary mt-4">Buscar</button>
        </div>
    </form>

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="post">
                <select name="idUsuario" class="form-select">
                    <option value="">Filtrar por usuario</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id ?>"><?= htmlspecialchars($user->usuario) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="buscarPorUsuario" class="btn btn-secondary mt-2">
                    Filtrar
                </button>
            </form>
        </div>
        <div class="col-md-6">
            <form method="post">
                <select name="idCliente" class="form-select">
                    <option value="">Filtrar por cliente</option>
                    <?php foreach ($clients as $client): ?>
                    <option value="<?= $client->id ?>"><?= htmlspecialchars($client->nombre) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="buscarPorCliente" class="btn btn-secondary mt-2">
                    Filtrar
                </button>
            </form>
        </div>
    </div>

    <?php include __DIR__.'/../shared/stats_cards.php'; ?>

    <?php if (!empty($sales)): ?>
    <div class="table-responsive">
        <table class="table">
            <!-- Tabla de reportes similar a la anterior -->
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-warning">No se encontraron ventas</div>
    <?php endif; ?>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>