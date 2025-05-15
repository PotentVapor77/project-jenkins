<table class="table">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user->usuario) ?></td>
            <td><?= htmlspecialchars($user->nombre) ?></td>
            <td><?= htmlspecialchars($user->telefono) ?></td>
            <td><?= htmlspecialchars($user->direccion) ?></td>
            <td>
                <a href="/users/edit/<?= $user->id ?>" class="btn btn-info">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="/users/delete/<?= $user->id ?>" class="btn btn-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>