<?= view('admin/partials/header', ['title' => 'Mon compte']) ?>

<h1>Mon compte OP</h1>

<?php if ($success): ?>
    <div class="message success"><?= esc($success) ?></div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="message error">
        <?php foreach ($errors as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="stats">
    <div class="stat">Client<strong><?= esc(trim($compte['nom'] . ' ' . $compte['prenom'])) ?></strong></div>
    <div class="stat">Telephone<strong><?= esc($compte['telephone']) ?></strong></div>
    <div class="stat">Solde<strong><?= number_format((float) $compte['solde'], 0, ',', ' ') ?> Ar</strong></div>
</div>

<div class="panel">
    <h2>Faire une operation</h2>

    <div class="grid">
        <form method="post" action="/compte/depot">
            <h3>Depot</h3>
            <label for="depot_montant">Montant</label>
            <input id="depot_montant" name="montant" type="number" min="1" step="1" required>
            <button type="submit">Deposer</button>
        </form>

        <form method="post" action="/compte/retrait">
            <h3>Retrait</h3>
            <label for="retrait_montant">Montant</label>
            <input id="retrait_montant" name="montant" type="number" min="1" step="1" required>
            <button type="submit">Retirer</button>
        </form>

        <form method="post" action="/compte/transfert">
            <h3>Transfert</h3>
            <label for="transfert_telephone">Telephone(s) destinataire(s)</label>
            <textarea id="transfert_telephone" name="telephone_destinataire" placeholder="0381122334, 0341234567" required><?= esc(old('telephone_destinataire')) ?></textarea>
            <p class="field-help">Separez plusieurs numeros par une virgule, un espace ou une ligne. Le montant sera divise entre eux.</p>

            <label for="transfert_montant">Montant</label>
            <input id="transfert_montant" name="montant" type="number" min="1" step="1" value="<?= esc(old('montant')) ?>" required>

            <button type="submit">Transferer</button>
        </form>
    </div>
</div>

<div class="panel">
    <h2>Historique</h2>

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
        <?php if ($historiques === []): ?>
            <tr><td colspan="5">Aucun historique.</td></tr>
        <?php endif; ?>
        <?php foreach ($historiques as $historique): ?>
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

<?= view('admin/partials/footer') ?>
