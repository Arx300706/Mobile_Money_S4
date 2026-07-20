<?= view('admin/partials/header', ['title' => 'Montants a envoyer aux operateurs']) ?>

<div class="site-section">
    <div class="section-heading">
        <div>
            <h1>Montants a envoyer aux operateurs</h1>
            <p>Suit les transferts a regler selon l'operateur destinataire.</p>
        </div>
    </div>

    <div class="panel">
        <form method="get" action="/SituationMontantOperateur">
            <div class="grid">
                <div>
                    <label for="date_debut">Date debut</label>
                    <input id="date_debut" name="date_debut" type="date" value="<?= esc($filters['date_debut'] ?? '') ?>">
                </div>
                <div>
                    <label for="date_fin">Date fin</label>
                    <input id="date_fin" name="date_fin" type="date" value="<?= esc($filters['date_fin'] ?? '') ?>">
                </div>
                <div class="actions">
                    <button type="submit">Filtrer</button>
                    <a class="button secondary" href="/SituationMontantOperateur">Reinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    <div class="stats">
        <div class="stat">Montant total<strong><?= number_format((float) $totalMontant, 0, ',', ' ') ?> Ar</strong></div>
        <div class="stat">Transferts<strong><?= esc($totalTransferts) ?></strong></div>
    </div>

    <div class="panel">
        <h2>Montant par operateur</h2>
        <table>
            <thead>
            <tr>
                <th>Operateur destinataire</th>
                <th>Nombre de transferts</th>
                <th>Montant a envoyer</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($summary === []): ?>
                <tr><td colspan="3">Aucun transfert pour ce filtre.</td></tr>
            <?php endif; ?>
            <?php foreach ($summary as $row): ?>
                <tr>
                    <td><?= esc($row['operateur_destinataire']) ?></td>
                    <td><?= esc($row['nombre_transferts']) ?></td>
                    <td><?= number_format((float) $row['montant_total'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>Detail des transferts</h2>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Operateur destinataire</th>
                <th>Expediteur</th>
                <th>Destinataire</th>
                <th>Montant</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($details === []): ?>
                <tr><td colspan="5">Aucun transfert.</td></tr>
            <?php endif; ?>
            <?php foreach ($details as $transaction): ?>
                <tr>
                    <td><?= esc($transaction['date']) ?></td>
                    <td><?= esc($transaction['operateur_destinataire']) ?></td>
                    <td><?= esc(trim($transaction['source_nom'] . ' ' . $transaction['source_prenom'])) ?> - <?= esc($transaction['source_telephone']) ?></td>
                    <td><?= esc(trim($transaction['destinataire_nom'] . ' ' . $transaction['destinataire_prenom'])) ?> - <?= esc($transaction['destinataire_telephone']) ?></td>
                    <td><?= number_format((float) $transaction['montant'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/partials/footer') ?>
