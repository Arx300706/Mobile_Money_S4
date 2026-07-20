<?= view('admin/partials/header', ['title' => 'Produits']) ?>

<h1>CRUD des produits</h1>

<?php if ($success): ?>
    <div class="message success"><?= esc($success) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="message error">
        <?php foreach ($errors as $error): ?>
            <?= esc($error) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p><a class="button" href="/admin/produits/create">Ajouter un produit</a></p>

<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Designation</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($produits === []): ?>
            <tr><td colspan="5">Aucun produit.</td></tr>
        <?php endif; ?>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= esc($produit['id']) ?></td>
                <td><?= esc($produit['designation']) ?></td>
                <td><?= number_format((float) $produit['prix'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($produit['stock']) ?></td>
                <td>
                    <a class="button secondary" href="/admin/produits/edit/<?= esc($produit['id']) ?>">Modifier</a>
                    <form method="post" action="/admin/produits/delete/<?= esc($produit['id']) ?>" style="display:inline">
                        <button class="danger" type="submit" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
