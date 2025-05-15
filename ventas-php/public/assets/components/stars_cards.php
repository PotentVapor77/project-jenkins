<div class="card-deck row mb-3">
    <?php foreach ($stats as $stat): ?>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="fa <?= $stat['icono'] ?>"></i>
                    <?= $stat['titulo'] ?>
                </h4>
                <h2><?= $stat['total'] ?></h2>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>