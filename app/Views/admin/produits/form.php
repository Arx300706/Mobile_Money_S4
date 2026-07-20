<?= view('admin/partials/header', ['title' => $title]) ?>

<h1><?= esc($title) ?></h1>

<?php if ($errors !== []): ?>
    <div class="message error">
        <?php foreach ($errors as $error): ?>
            <?= esc($error) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="panel">
    <form method="post" action="<?= esc($action) ?>">
        <label for="designation">Designation</label>
        <input id="designation" name="designation" value="<?= esc(old('designation') ?? ($produit['designation'] ?? '')) ?>" required>

        <label for="prix">Prix</label>
        <input id="prix" name="prix" type="number" min="1" step="0.01" value="<?= esc(old('prix') ?? ($produit['prix'] ?? '')) ?>" required>

        <label for="stock">Stock</label>
        <input id="stock" name="stock" type="number" min="0" value="<?= esc(old('stock') ?? ($produit['stock'] ?? '')) ?>" required>

        <button type="submit">Enregistrer</button>
        <a class="button secondary" href="/admin/produits">Annuler</a>
    </form>
</div>

<?= view('admin/partials/footer') ?>
