<?= view('admin/partials/header', ['title' => 'Accueil']) ?>

<h1>Choix de caisse</h1>

<?php if ($error): ?>
    <div class="message error"><?= esc($error) ?></div>
<?php endif; ?>

<div class="panel">
    <form method="post" action="/caisseSelect">
        <label for="caisse_id">Caisse</label>
        <select id="caisse_id" name="caisse_id" required>
            <option value="">Choisir une caisse</option>
            <?php foreach ($caisses as $caisse): ?>
                <option value="<?= esc($caisse['id']) ?>">
                    Caisse #<?= esc($caisse['id']) ?> - <?= number_format((float) $caisse['montant_total'], 0, ',', ' ') ?> Ar
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Continuer</button>
    </form>
</div>

<?= view('admin/partials/footer') ?>
