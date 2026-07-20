<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie des achats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
            background: #f5f7f8;
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
        }

        .header,
        .panel {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            align-items: center;
        }

        h1,
        h2 {
            margin-top: 0;
        }

        .menu {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }

        .menu a {
            color: #0b62a3;
            text-decoration: none;
            margin-right: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        select,
        input {
            width: 100%;
            max-width: 420px;
            padding: 9px;
            border: 1px solid #bbb;
            border-radius: 4px;
            margin-bottom: 14px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 16px;
            border: 0;
            border-radius: 4px;
            background: #0b62a3;
            color: #fff;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eef3f6;
        }

        .right {
            text-align: right;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .success {
            background: #e8f6ed;
            color: #1d6b35;
            border: 1px solid #bfe3cb;
        }

        .error {
            background: #fdecec;
            color: #9d1c1c;
            border: 1px solid #f3baba;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <h1>Saisie des achats</h1>
            <strong>Client : <?= esc($clientNom) ?></strong><br>
            <strong>Caisse choisie : Caisse #<?= esc($caisse['id']) ?></strong><br>
            Montant caisse : <?= number_format((float) $caisse['montant_total'], 0, ',', ' ') ?> Ar<br>
            Date : <?= esc($caisse['date']) ?>
        </div>

        <div>
            <a href="/accueil">Changer de caisse</a>
        </div>
    </div>

    <div class="panel">
        <h2>Partie en haut</h2>

        <div class="menu">
            <a href="/achat">Saisie achat</a>
        </div>

        <?php if ($success): ?>
            <div class="message success"><?= esc($success) ?></div>
        <?php endif; ?>

        <?php if ($errors !== []): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <?= esc($error) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/achat/store">
            <label for="produit_id">Produit</label>
            <select name="produit_id" id="produit_id" required>
                <option value="">Choisir un produit</option>
                <?php foreach ($produits as $produit): ?>
                    <option value="<?= esc($produit['id']) ?>" <?= old('produit_id') == $produit['id'] ? 'selected' : '' ?>>
                        <?= esc($produit['designation']) ?> -
                        <?= number_format((float) $produit['prix'], 0, ',', ' ') ?> Ar -
                        stock <?= esc($produit['stock']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantite">Quantite</label>
            <input type="number" name="quantite" id="quantite" min="1" value="<?= esc(old('quantite') ?? 1) ?>" required>

            <button type="submit">Ajouter l'achat</button>
        </form>

        <form method="post" action="/achat/cloturer" style="margin-top: 12px;">
            <button type="submit">Cloturer achat</button>
        </form>
    </div>

    <div class="panel">
        <h2>Partie en bas</h2>

        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th>Quantite</th>
                <th>Prix unitaire</th>
                <th class="right">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($achats === []): ?>
                <tr>
                    <td colspan="4">Aucun achat saisi pour cette caisse.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($achats as $achat): ?>
                <tr>
                    <td><?= esc($achat['designation']) ?></td>
                    <td><?= esc($achat['quantite']) ?></td>
                    <td><?= number_format((float) $achat['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                    <td class="right">
                        <?= number_format((float) ($achat['quantite'] * $achat['prix_unitaire']), 0, ',', ' ') ?> Ar
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="right">Total achats</th>
                <th class="right"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
</body>
</html>
