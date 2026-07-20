<?= view('admin/partials/header', ['title' => 'Nouvelle operation']) ?>

<div class="type-page">
    <section class="type-hero">
        <h1>Nouvelle operation</h1>
        <p>Ajoute un type d'operation et, si besoin, ses premieres tranches de frais fixes.</p>
    </section>

    <?php if ($errors !== []): ?>
        <div class="message error">
            <?php foreach ($errors as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="panel type-panel">
        <form method="post" action="/TypeOperation/store">
            <label for="nom">Nom de la nouvelle operation</label>
            <input id="nom" name="nom" type="text" maxlength="50" placeholder="Depot, Retrait, Transfert" value="<?= esc(old('nom')) ?>" required>

            <h3>Tranches initiales</h3>
            <table>
                <thead>
                <tr>
                    <th>Operateur</th>
                    <th>Min</th>
                    <th>Max</th>
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
                        <td><input name="montant_frais[]" type="number" min="0" step="0.01" placeholder="200"></td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit">Creer</button>
                <a class="button secondary" href="/TypeOperation">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?= view('admin/partials/footer') ?>
