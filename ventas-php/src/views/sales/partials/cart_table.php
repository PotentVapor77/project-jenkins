<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Productos</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item->codigo) ?></td>
                        <td><?= htmlspecialchars($item->nombre) ?></td>
                        <td>$<?= number_format($item->venta, 2) ?></td>
                        <td><?= $item->cantidad ?></td>
                        <td>$<?= number_format($item->venta * $item->cantidad, 2) ?></td>
                        <td>
                            <a href="/sales/remove-product/<?= $item->id ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Quitar producto?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>