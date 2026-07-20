<?= view('admin/partials/header', ['title' => 'Operateurs']) ?>

<h1>Operateurs</h1>
<form action="/TypeOperation" method="get">
    <button type="submit" class="secondary">Voir les types d'operations</button>
</form>

<?php if ($success): ?>
    <div class="message success"><?= esc($success) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="message error">
        <?php foreach ($errors as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="panel">
    <h2>Prefixes de notre operateur OP</h2>
    <form method="post" action="/operateur/store">
        <label for="prefixe">Prefixes telephone</label>
        <input id="prefixe" name="prefixe" type="text" inputmode="numeric" pattern="[0-9,;\s]+" value="<?= esc(old('prefixe')) ?>" placeholder="Ex: 34, 38" required>
        <p class="field-help">Ces prefixes seront classes comme op. Les autres seront classes comme Autres Operateurs.</p>

        <button type="submit">Enregistrer</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prefixe</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($operateurs === []): ?>
            <tr><td colspan="4">Aucun operateur.</td></tr>
        <?php endif; ?>
        <?php foreach ($operateurs as $operateur): ?>
            <tr>
                <td><?= esc($operateur['id']) ?></td>
                <td><?= esc($operateur['nom']) ?></td>
                <td><?= esc($operateur['prefixe']) ?></td>
                <td>
                    <div class="actions">
                        <a class="button secondary" href="/operateur/edit/<?= esc($operateur['id']) ?>">Modifier</a>
                        <form method="post" action="/operateur/delete/<?= esc($operateur['id']) ?>" class="inline-form">
                            <button class="danger" type="submit">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
