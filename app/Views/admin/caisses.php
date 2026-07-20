<?= view('admin/partials/header', ['title' => 'Caisses']) ?>

<h1>Details des caisses</h1>

<div class="panel">
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
                <td><a class="button" href="/admin/caisses/<?= esc($caisse['id']) ?>">Voir achats</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= view('admin/partials/footer') ?>
