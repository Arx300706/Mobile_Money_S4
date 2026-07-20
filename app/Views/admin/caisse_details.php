<?= view('admin/partials/header', ['title' => 'Details caisse']) ?>

<h1>Caisse #<?= esc($caisse['id']) ?></h1>

<div class="panel">
    <p><strong>Montant total :</strong> <?= number_format((float) $caisse['montant_total'], 0, ',', ' ') ?> Ar</p>
    <p><strong>Date :</strong> <?= esc($caisse['date']) ?></p>
</div>

<div class="panel">
    <h2>Achats de cette caisse</h2>
    <?= view('admin/partials/achats_table', ['achats' => $achats]) ?>
</div>

<?= view('admin/partials/footer') ?>
