<?php include __DIR__.'/../shared/header.php'; ?>
<?php include __DIR__.'/../shared/navbar.php'; ?>

<div class="container mt-3">
    <h1>
        <a href="/products/create" class="btn btn-success btn-lg">
            <i class="fa fa-plus"></i> Agregar
        </a>
        Productos
    </h1>
    
    <?php include __DIR__.'/../shared/stats_cards.php'; ?>
    
    <form action="/products" method="post" class="input-group mb-3 mt-3">
        <input name="nombreProducto" type="text" class="form-control" 
               placeholder="Buscar producto..." autofocus>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-search"></i> Buscar
        </button>
    </form>
    
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Ganancia</th>
                <th>Existencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product->codigo) ?></td>
                <td><?= htmlspecialchars($product->nombre) ?></td>
                <td>$<?= number_format($product->compra, 2) ?></td>
                <td>$<?= number_format($product->venta, 2) ?></td>
                <td>$<?= number_format($product->venta - $product->compra, 2) ?></td>
                <td><?= $product->existencia ?></td>
                <td>
                    <a href="/products/edit/<?= $product->id ?>" class="btn btn-info">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="/products/delete/<?= $product->id ?>" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__.'/../shared/footer.php'; ?>