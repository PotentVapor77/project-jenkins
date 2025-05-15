<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<div class="container">
    <h1>Nueva Venta</h1>
    
    <?php include __DIR__.'/../shared/flash.php'; ?>
    
    <form action="/sales/add-product" method="post" class="row mb-3">
        <div class="col-md-10">
            <input type="text" name="codigo" class="form-control form-control-lg" 
                   placeholder="Código de barras" required autofocus>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="fa fa-plus"></i> Agregar
            </button>
        </div>
    </form>

    <?php if (!empty($cart)): ?>
        <?php include __DIR__.'/partials/cart_table.php'; ?>
        <?php include __DIR__.'/partials/client_selection.php'; ?>
        
        <div class="text-center mt-4">
            <h2>Total: $<?= number_format($total, 2) ?></h2>
            <div class="d-grid gap-2 d-md-block">
                <a href="/sales/process" class="btn btn-primary btn-lg me-md-2">
                    <i class="fa fa-check"></i> Terminar Venta
                </a>
                <a href="/sales/cancel" class="btn btn-danger btn-lg">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Agrega productos para comenzar la venta</div>
    <?php endif; ?>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>