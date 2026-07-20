<?= view('admin/partials/header', ['title' => 'Operateurs']) ?>

<h1>Operateurs</h1>
<form action="/TypeOperation" method="get">
    <button type="submit" class="btn btn-warning btn-sm">Voir les types d'opérations</button>
</form>


<h2>Liste des opérateurs</h2>
<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prefixe</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($operateurs === []): ?>
            <tr><td colspan="3">Aucun operateur.</td></tr>
        <?php endif; ?>
        <?php foreach ($operateurs as $operateur): ?>
            <tr>
                <td><?= esc($operateur['id']) ?></td>
                <td><?= esc($operateur['nom']) ?></td>
                <td><?= esc($operateur['prefixe']) ?></td>
                <td>
                    <form action="<?= site_url('/operateur/edit/' . $operateur['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-warning btn-sm">Modifier</button>
                    </form>
                    <form action="<?= site_url('/operateur/delete/' . $operateur['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet opérateur ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<h2>Créer un nouvel opérateur</h2>
<form action="<?= site_url('/operateur/save') ?>" method="post">
    <div>
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required>
    </div>
    <div>
        <label for="prefixe">Prefixe:</label>
        <input type="text" id="prefixe" name="prefixe" placeholder="Doit être un entier unique." required>
    </div>
    <button type="submit">Créer</button>

<?= view('admin/partials/footer') ?>
