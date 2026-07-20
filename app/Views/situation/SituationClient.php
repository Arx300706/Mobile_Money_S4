<?= view('admin/partials/header', ['title' => 'Situation des clients']) ?>

<div class="site-section client-situation">
    <div class="section-heading">
        <div>
            <h1>Situation des clients</h1>
            <p>Consulte tous les comptes clients avec leur solde et leur historique.</p>
        </div>
    </div>

    <div class="stats">
        <div class="stat">Clients<strong><?= esc($totalClients) ?></strong></div>
        <div class="stat">Comptes actifs<strong><?= esc($totalComptes) ?></strong></div>
        <div class="stat">Solde total<strong><?= number_format((float) $totalSolde, 0, ',', ' ') ?> Ar</strong></div>
    </div>

    <div class="panel">
        <form method="get" action="/SituationClient">
            <div class="toolbar">
                <div class="toolbar-field">
                    <label for="client_id">Filtrer par client</label>
                    <select id="client_id" name="client_id">
                        <option value="0">Tous les clients</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= esc($client['id']) ?>" <?= (int) $selectedClientId === (int) $client['id'] ? 'selected' : '' ?>>
                                <?= esc(trim($client['nom'] . ' ' . $client['prenom'])) ?> - <?= esc($client['telephone']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="actions">
                    <button type="submit">Filtrer</button>
                    <a class="button secondary" href="/SituationClient">Reinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    <div class="panel">
        <h2>Liste des clients</h2>
        <table>
            <thead>
            <tr>
                <th>Numero</th>
                <th>Client</th>
                <th>Telephone</th>
                <th>Email</th>
                <th>Date creation</th>
                <th>Solde</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($clients === []): ?>
                <tr><td colspan="6">Aucun client.</td></tr>
            <?php endif; ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= esc($client['id']) ?></td>
                    <td><?= esc(trim($client['nom'] . ' ' . $client['prenom'])) ?></td>
                    <td><?= esc($client['telephone']) ?></td>
                    <td><?= esc($client['email']) ?></td>
                    <td><?= esc($client['date_creation'] ?? '-') ?></td>
                    <td><?= number_format((float) ($client['solde'] ?? 0), 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php foreach ($clientsHistoriques as $bloc): ?>
        <?php $client = $bloc['client']; ?>
        <div class="panel client-history">
            <div class="panel-title">
                <div>
                    <h2><?= esc(trim($client['nom'] . ' ' . $client['prenom'])) ?></h2>
                    <span class="muted"><?= esc($client['telephone']) ?> - Solde: <?= number_format((float) ($client['solde'] ?? 0), 0, ',', ' ') ?> Ar</span>
                </div>
            </div>

            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Operation</th>
                    <th>Montant</th>
                    <th>Solde avant</th>
                    <th>Solde apres</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($bloc['historiques'] === []): ?>
                    <tr><td colspan="5">Aucun historique pour ce client.</td></tr>
                <?php endif; ?>
                <?php foreach ($bloc['historiques'] as $historique): ?>
                    <tr>
                        <td><?= esc($historique['date']) ?></td>
                        <td><?= esc($historique['type_operation']) ?></td>
                        <td><?= number_format((float) $historique['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format((float) $historique['solde_avant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format((float) $historique['solde_apres'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>

<?= view('admin/partials/footer') ?>
