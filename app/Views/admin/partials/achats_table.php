<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Caisse</th>
        <th>Produit</th>
        <th>Quantite</th>
        <th>Prix unitaire</th>
        <th class="right">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($achats === []): ?>
        <tr><td colspan="7">Aucun achat.</td></tr>
    <?php endif; ?>
    <?php foreach ($achats as $achat): ?>
        <tr>
            <td><?= esc($achat['id']) ?></td>
            <td><?= esc($achat['client_nom'] ?? 'Client supprime') ?></td>
            <td>Caisse #<?= esc($achat['caisse_id']) ?></td>
            <td><?= esc($achat['designation'] ?? 'Produit supprime') ?></td>
            <td><?= esc($achat['quantite']) ?></td>
            <td><?= number_format((float) $achat['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
            <td class="right"><?= number_format((float) ($achat['quantite'] * $achat['prix_unitaire']), 0, ',', ' ') ?> Ar</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
