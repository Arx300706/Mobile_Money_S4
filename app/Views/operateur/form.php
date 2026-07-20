<?= view('admin/partials/header', ['title' => $title]) ?>

<h1><?= esc($title) ?></h1>

<?php if ($errors !== []): ?>
    <div class="message error">
        <?php foreach ($errors as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="panel">
    <form method="post" action="<?= esc($action) ?>">
        <label for="nom">Nom</label>
        <input id="nom" name="nom" type="text" maxlength="100" value="<?= esc(old('nom', $operateur['nom'] ?? '')) ?>" required>

        <label for="prefixe">Prefixe telephone</label>
        <input id="prefixe" name="prefixe" type="number" min="1" max="99" value="<?= esc(old('prefixe', $operateur['prefixe'] ?? '')) ?>" required>

        <button type="submit">Enregistrer</button>
        <a class="button secondary" href="/operateur">Annuler</a>
    </form>
</div>

<?= view('admin/partials/footer') ?>
