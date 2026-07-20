<?= view('admin/partials/header', ['title' => 'Administration']) ?>

<?php if ($success): ?>
    <div class="message success"><?= esc($success) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="message error"><?= esc($error) ?></div>
<?php endif; ?>

<h1>Tableau de bord admin</h1>

<div class="stats">
    <div class="stat">Produits<strong><?= esc($produitsCount) ?></strong></div>
    <div class="stat">Clients<strong><?= esc($clientsCount) ?></strong></div>
    <div class="stat">Caisses<strong><?= esc($caissesCount) ?></strong></div>
    <div class="stat">Achats<strong><?= esc($achatsCount) ?></strong></div>
</div>

<div class="panel">
    <h2>Details des caisses</h2>
    <table>
        <thead>
        <tr>
            <th>Caisse</th>
            <th>Montant total</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($caisses === []): ?>
            <tr><td colspan="4">Aucune caisse.</td></tr>
        <?php endif; ?>
        <?php foreach ($caisses as $caisse): ?>
            <tr>
                <td>Caisse #<?= esc($caisse['id']) ?></td>
                <td><?= number_format((float) $caisse['montant_total'], 0, ',', ' ') ?> Ar</td>
                <td><?= esc($caisse['date']) ?></td>
                <td><a class="button" href="/admin/caisses/<?= esc($caisse['id']) ?>">Voir details</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
