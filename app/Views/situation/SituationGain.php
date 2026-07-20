<?= view('admin/partials/header', ['title' => 'Situation des gains']) ?>

<div class="site-section">
    <div class="section-heading">
        <div>
            <h1>Situation des gains</h1>
            <p>Analyse les frais gagnes sur les retraits et transferts.</p>
        </div>
    </div>

    <div class="panel">
        <form method="get" action="/SituationGain">
            <div class="grid">
                <div>
                    <label for="date_debut">Date debut</label>
                    <input id="date_debut" name="date_debut" type="date" value="<?= esc($filters['date_debut'] ?? '') ?>">
                </div>
                <div>
                    <label for="date_fin">Date fin</label>
                    <input id="date_fin" name="date_fin" type="date" value="<?= esc($filters['date_fin'] ?? '') ?>">
                </div>
                <div>
                    <label for="type_operation_id">Operation</label>
                    <select id="type_operation_id" name="type_operation_id">
                        <option value="0">Retrait et transfert</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= esc($type['id']) ?>" <?= (int) $filters['type_operation_id'] === (int) $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="actions">
                    <button type="submit">Filtrer</button>
                    <a class="button secondary" href="/SituationGain">Reinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    <div class="stats">
        <div class="stat">Gain total<strong><?= number_format((float) $totalGain, 0, ',', ' ') ?> Ar</strong></div>
        <div class="stat">Montant traite<strong><?= number_format((float) $totalMontant, 0, ',', ' ') ?> Ar</strong></div>
        <div class="stat">Operations<strong><?= esc($totalOperations) ?></strong></div>
    </div>

    <div class="stats">
        <?php foreach ($totauxParOperateur as $totalOperateur): ?>
            <div class="stat">
                <?= esc($totalOperateur['categorie_operateur']) ?>
                <strong><?= number_format((float) $totalOperateur['gain_total'], 0, ',', ' ') ?> Ar</strong>
                <span class="muted"><?= number_format((float) $totalOperateur['montant_total'], 0, ',', ' ') ?> Ar / <?= esc($totalOperateur['nombre_operations']) ?> operation(s)</span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="panel">
        <h2>Gain par operateur et type d'operation</h2>
        <table>
            <thead>
            <tr>
                <th>Operateur</th>
                <th>Operation</th>
                <th>Nombre</th>
                <th>Montant traite</th>
                <th>Gain</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($summary === []): ?>
                <tr><td colspan="5">Aucun gain pour ce filtre.</td></tr>
            <?php endif; ?>
            <?php foreach ($summary as $row): ?>
                <tr>
                    <td><?= esc($row['categorie_operateur']) ?></td>
                    <td><?= esc($row['type_operation']) ?></td>
                    <td><?= esc($row['nombre_operations']) ?></td>
                    <td><?= number_format((float) $row['montant_total'], 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format((float) $row['gain_total'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>Detail des transactions</h2>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Operateur</th>
                <th>Operation</th>
                <th>Client</th>
                <th>Telephone</th>
                <th>Montant</th>
                <th>Frais gagne</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($details === []): ?>
                <tr><td colspan="7">Aucune operation.</td></tr>
            <?php endif; ?>
            <?php foreach ($details as $transaction): ?>
                <tr>
                    <td><?= esc($transaction['date']) ?></td>
                    <td><?= esc($transaction['categorie_operateur']) ?></td>
                    <td><?= esc($transaction['type_operation']) ?></td>
                    <td><?= esc(trim($transaction['client_nom'] . ' ' . $transaction['client_prenom'])) ?></td>
                    <td><?= esc($transaction['telephone']) ?></td>
                    <td><?= number_format((float) $transaction['montant'], 0, ',', ' ') ?> Ar</td>
                    <td><?= number_format((float) $transaction['montant_frais'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/partials/footer') ?>
