<?= view('admin/partials/header', ['title' => 'Types operations et frais']) ?>

<div class="type-page">
    <section class="type-hero">
        <h1>Types d'operations et baremes de frais</h1>
        <p>Configure les operations Mobile Money et les frais fixes par operateur et tranche.</p>
    </section>

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

    <div class="type-layout">
        <div>
            <div class="panel type-panel">
                <div class="panel-title">
                    <div>
                        <h2>Types d'operations</h2>
                        <span class="muted"><?= esc(count($types)) ?> type(s) configure(s)</span>
                    </div>
                    <a class="button" href="/TypeOperation/create">Nouvelle operation</a>
                </div>

                <table>
                    <thead>
                    <tr>
                        <th>Numero</th>
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
                                <form id="type-update-<?= esc($type['id']) ?>" method="post" action="/TypeOperation/update/<?= esc($type['id']) ?>">
                                    <input name="nom" type="text" maxlength="50" value="<?= esc($type['nom']) ?>" required>
                                </form>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="button secondary" href="/TypeOperation?operation_id=<?= esc($type['id']) ?>">Voir tranches</a>
                                    <button form="type-update-<?= esc($type['id']) ?>" type="submit">Modifier</button>
                                    <form method="post" action="/TypeOperation/delete/<?= esc($type['id']) ?>" class="inline-form">
                                        <button class="danger" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="panel type-panel">
                <div class="panel-title">
                    <div>
                        <h2>Filtrer les tranches</h2>
                        <span class="muted">Affiche les baremes d'un type precis.</span>
                    </div>
                </div>

                <form method="get" action="/TypeOperation">
                    <div class="toolbar">
                        <div class="toolbar-field">
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
                        <div class="actions">
                            <button type="submit">Filtrer</button>
                            <a class="button secondary" href="/TypeOperation">Reinitialiser</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="panel type-panel">
                <div class="panel-title">
                    <div>
                        <h2>Ajouter un bareme</h2>
                        <span class="muted">Cree une tranche fixe sans chevauchement.</span>
                    </div>
                </div>

                <form method="post" action="/frais/store">
                    <div class="compact-grid">
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
                                <?php if ($operateurs === []): ?>
                                    <option value="">Aucun operateur OP configure</option>
                                <?php endif; ?>
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
                            <label for="montant_frais">Valeur</label>
                            <input id="montant_frais" name="montant_frais" type="number" min="0" step="0.01" required>
                        </div>
                    </div>
                    <button class="primary-action" type="submit">Ajouter le bareme</button>
                </form>
            </div>

            <div class="panel type-panel">
                <div class="panel-title">
                    <div>
                        <h2>Tableau des tranches</h2>
                        <span class="muted"><?= esc(count($frais)) ?> tranche(s) affichee(s)</span>
                    </div>
                </div>

                <table>
                    <thead>
                    <tr>
                        <th>Operateur</th>
                        <th>Type</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Valeur</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($frais === []): ?>
                        <tr><td colspan="6">Aucune tranche pour ce filtre.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($frais as $bareme): ?>
                        <tr>
                            <td>
                                <select form="frais-update-<?= esc($bareme['id']) ?>" name="id_operateur" required>
                                    <?php if ($operateurs === []): ?>
                                        <option value="">Aucun operateur OP configure</option>
                                    <?php endif; ?>
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
                                <input form="frais-update-<?= esc($bareme['id']) ?>" name="montant_frais" type="number" min="0" step="0.01" value="<?= esc($bareme['montant_frais']) ?>" required>
                                <span class="badge">Ar</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button form="frais-update-<?= esc($bareme['id']) ?>" type="submit">Modifier</button>
                                    <form id="frais-delete-<?= esc($bareme['id']) ?>" method="post" action="/frais/delete/<?= esc($bareme['id']) ?>" class="inline-form">
                                        <button class="danger" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <?php foreach ($frais as $bareme): ?>
                    <form id="frais-update-<?= esc($bareme['id']) ?>" method="post" action="/frais/update/<?= esc($bareme['id']) ?>"></form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= view('admin/partials/footer') ?>
