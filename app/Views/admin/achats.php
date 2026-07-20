<?= view('admin/partials/header', ['title' => 'Achats']) ?>

<h1>Liste des achats</h1>

<div class="panel">
    <?= view('admin/partials/achats_table', ['achats' => $achats]) ?>
</div>

<?= view('admin/partials/footer') ?>
