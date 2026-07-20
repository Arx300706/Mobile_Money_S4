<?= view('admin/partials/header', ['title' => 'Clients']) ?>

<h1>Liste des clients</h1>

<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($clients === []): ?>
            <tr><td colspan="3">Aucun client.</td></tr>
        <?php endif; ?>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= esc($client['id']) ?></td>
                <td><?= esc($client['nom']) ?></td>
                <td><?= esc($client['date']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
