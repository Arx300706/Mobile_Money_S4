<?= view('admin/partials/header', ['title' => 'Promotion frais']) ?>

<div class="site-section">
    <div class="section-heading">
        <div>
            <h1>Promotion frais de transfert</h1>
            <p>Configure la reduction appliquee aux transferts vers un numero du meme operateur.</p>
        </div>
    </div>

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
        <?php if (! $promotion): ?>
            <p>Configuration promotion introuvable.</p>
        <?php else: ?>
            <form method="post" action="/promotion/update">
                <div class="grid">
                    <div>
                        <label for="type_promotion">Type de promotion</label>
                        <select id="type_promotion" name="type_promotion" required>
                            <option value="pourcentage" <?= ($promotion['type_promotion'] ?? '') === 'pourcentage' ? 'selected' : '' ?>>Pourcentage</option>
                            <option value="fixe" <?= ($promotion['type_promotion'] ?? '') === 'fixe' ? 'selected' : '' ?>>Fixe</option>
                        </select>
                    </div>
                    <div>
                        <label for="valeur">Valeur</label>
                        <input id="valeur" name="valeur" type="number" min="0" step="0.01" value="<?= esc($promotion['valeur']) ?>" required>
                    </div>
                    <div>
                        <label for="actif">Etat</label>
                        <select id="actif" name="actif">
                            <option value="1" <?= (int) ($promotion['actif'] ?? 0) === 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (int) ($promotion['actif'] ?? 0) === 0 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <button type="submit">Modifier la promotion</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?= view('admin/partials/footer') ?>
