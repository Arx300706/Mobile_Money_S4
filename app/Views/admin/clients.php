<?= view('admin/partials/header', ['title' => 'Clients']) ?>

<h1>Liste des clients</h1>

<div class="panel">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Date naissance</th>
            <th>Adresse</th>
            <th>Email</th>
            <th>Telephone</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($clients === []): ?>
            <tr><td colspan="7">Aucun client.</td></tr>
        <?php endif; ?>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= esc($client['id']) ?></td>
                <td><?= esc($client['nom']) ?></td>
                <td><?= esc($client['prenom'] ?? '') ?></td>
                <td><?= esc($client['date_naissance'] ?? '') ?></td>
                <td><?= esc($client['adresse'] ?? '') ?></td>
                <td><?= esc($client['email'] ?? '') ?></td>
                <td><?= esc($client['telephone'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
