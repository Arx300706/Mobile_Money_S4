<?= view('admin/partials/header', ['title' => 'Operateurs']) ?>

<h1>Operateurs</h1>

<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prefixe</th>
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
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
