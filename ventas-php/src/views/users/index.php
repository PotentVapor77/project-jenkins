

<?php include __DIR__ . '/../shared/header.php'; ?>
<?php include __DIR__ . '/../shared/navbar.php'; ?>

<div class="container">
    <h1>
        <a href="/users/create" class="btn btn-success btn-lg">
            <i class="fa fa-plus"></i> Agregar
        </a>
        Usuarios
    </h1>
    
    <?php include __DIR__ . '/partials/users_table.php'; ?>
</div>

<?php include __DIR__ . '/../shared/footer.php'; ?>