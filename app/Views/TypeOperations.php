<?= view('admin/partials/header', ['title' => 'Types operations et frais']) ?>

<h1>Types d'operations et baremes de frais</h1>

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

<div class="panel">
    <h2>Creer un type d'operation avec des tranches</h2>

    <form method="post" action="/TypeOperation/store">
        <label for="nom">Nom du type d'operation</label>
        <input id="nom" name="nom" type="text" maxlength="50" placeholder="Depot, Retrait, Transfert" required>

        <h3>Tranches initiales</h3>
        <table>
            <thead>
            <tr>
                <th>Operateur</th>
                <th>Min</th>
                <th>Max</th>
                <th>Type frais</th>
                <th>Valeur</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <td>
                        <select name="id_operateur[]">
                            <?php foreach ($operateurs as $operateur): ?>
                                <option value="<?= esc($operateur['id']) ?>"><?= esc($operateur['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input name="tranche_min[]" type="number" min="0" step="1" placeholder="0"></td>
                    <td><input name="tranche_max[]" type="number" min="0" step="1" placeholder="10000"></td>
                    <td>
                        <select name="type_frais[]">
                            <option value="fixe">Fixe</option>
                            <option value="pourcentage">Pourcentage</option>
                        </select>
                    </td>
                    <td><input name="montant_frais[]" type="number" min="0" step="0.01" placeholder="200 ou 2.5"></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <button type="submit">Creer</button>
    </form>
</div>

<div class="panel">
    <h2>Types d'operations</h2>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($types === []): ?>
            <tr><td colspan="3">Aucun type d'operation.</td></tr>
        <?php endif; ?>
        <?php foreach ($types as $type): ?>
            <tr>
                <td><?= esc($type['id']) ?></td>
                <td>
                    <input form="type-update-<?= esc($type['id']) ?>" name="nom" type="text" maxlength="50" value="<?= esc($type['nom']) ?>" required>
                </td>
                <td>
                    <div class="actions">
                        <a class="button secondary" href="/TypeOperation?operation_id=<?= esc($type['id']) ?>">Voir tranches</a>
                        <button form="type-update-<?= esc($type['id']) ?>" type="submit">Modifier</button>
                        <button form="type-delete-<?= esc($type['id']) ?>" class="danger" type="submit">Supprimer</button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php foreach ($types as $type): ?>
        <form id="type-update-<?= esc($type['id']) ?>" method="post" action="/TypeOperation/update/<?= esc($type['id']) ?>"></form>
        <form id="type-delete-<?= esc($type['id']) ?>" method="post" action="/TypeOperation/delete/<?= esc($type['id']) ?>"></form>
    <?php endforeach; ?>
</div>

<div class="panel">
    <h2>Filtrer les tranches</h2>

    <form method="get" action="/TypeOperation">
        <div class="grid">
            <div>
                <label for="operation_id">Type d'operation</label>
                <select id="operation_id" name="operation_id">
                    <option value="0">Tous les types</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= esc($type['id']) ?>" <?= (int) $selectedTypeId === (int) $type['id'] ? 'selected' : '' ?>>
                            <?= esc($type['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit">Filtrer</button>
                <a class="button secondary" href="/TypeOperation">Reinitialiser</a>
            </div>
        </div>
    </form>
</div>

<div class="panel">
    <h2>Ajouter un bareme</h2>

    <form method="post" action="/frais/store">
        <div class="grid">
            <div>
                <label for="id_type_operations">Type</label>
                <select id="id_type_operations" name="id_type_operations" required>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= esc($type['id']) ?>" <?= (int) $selectedTypeId === (int) $type['id'] ? 'selected' : '' ?>>
                            <?= esc($type['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="id_operateur">Operateur</label>
                <select id="id_operateur" name="id_operateur" required>
                    <?php foreach ($operateurs as $operateur): ?>
                        <option value="<?= esc($operateur['id']) ?>"><?= esc($operateur['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tranche_min">Min</label>
                <input id="tranche_min" name="tranche_min" type="number" min="0" step="1" required>
            </div>
            <div>
                <label for="tranche_max">Max</label>
                <input id="tranche_max" name="tranche_max" type="number" min="0" step="1" required>
            </div>
            <div>
                <label for="type_frais">Type frais</label>
                <select id="type_frais" name="type_frais" required>
                    <option value="fixe">Fixe</option>
                    <option value="pourcentage">Pourcentage</option>
                </select>
            </div>
            <div>
                <label for="montant_frais">Valeur</label>
                <input id="montant_frais" name="montant_frais" type="number" min="0" step="0.01" required>
            </div>
        </div>
        <button type="submit">Ajouter</button>
    </form>
</div>

<div class="panel">
    <h2>Tableau des tranches</h2>

    <table>
        <thead>
        <tr>
            <th>Operateur</th>
            <th>Type</th>
            <th>Min</th>
            <th>Max</th>
            <th>Nature</th>
            <th>Valeur</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($frais === []): ?>
            <tr><td colspan="7">Aucune tranche pour ce filtre.</td></tr>
        <?php endif; ?>
        <?php foreach ($frais as $bareme): ?>
            <tr>
                <td>
                    <select form="frais-update-<?= esc($bareme['id']) ?>" name="id_operateur" required>
                        <?php foreach ($operateurs as $operateur): ?>
                            <option value="<?= esc($operateur['id']) ?>" <?= (int) $bareme['id_operateur'] === (int) $operateur['id'] ? 'selected' : '' ?>>
                                <?= esc($operateur['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select form="frais-update-<?= esc($bareme['id']) ?>" name="id_type_operations" required>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= esc($type['id']) ?>" <?= (int) $bareme['id_type_operations'] === (int) $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input form="frais-update-<?= esc($bareme['id']) ?>" name="tranche_min" type="number" min="0" step="1" value="<?= esc($bareme['tranche_min']) ?>" required></td>
                <td><input form="frais-update-<?= esc($bareme['id']) ?>" name="tranche_max" type="number" min="0" step="1" value="<?= esc($bareme['tranche_max']) ?>" required></td>
                <td>
                    <select form="frais-update-<?= esc($bareme['id']) ?>" name="type_frais" required>
                        <option value="fixe" <?= ($bareme['type_frais'] ?? 'fixe') === 'fixe' ? 'selected' : '' ?>>Fixe</option>
                        <option value="pourcentage" <?= ($bareme['type_frais'] ?? 'fixe') === 'pourcentage' ? 'selected' : '' ?>>Pourcentage</option>
                    </select>
                </td>
                <td><input form="frais-update-<?= esc($bareme['id']) ?>" name="montant_frais" type="number" min="0" step="0.01" value="<?= esc($bareme['montant_frais']) ?>" required></td>
                <td>
                    <div class="actions">
                        <button form="frais-update-<?= esc($bareme['id']) ?>" type="submit">Modifier</button>
                        <button form="frais-delete-<?= esc($bareme['id']) ?>" class="danger" type="submit">Supprimer</button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php foreach ($frais as $bareme): ?>
        <form id="frais-update-<?= esc($bareme['id']) ?>" method="post" action="/frais/update/<?= esc($bareme['id']) ?>"></form>
        <form id="frais-delete-<?= esc($bareme['id']) ?>" method="post" action="/frais/delete/<?= esc($bareme['id']) ?>"></form>
    <?php endforeach; ?>
</div>

<?= view('admin/partials/footer') ?>
