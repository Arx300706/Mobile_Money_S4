<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe admin</title>
    <link rel="stylesheet" href="/style/styles.css">
</head>
<body class="auth-page">
<div class="page">
    <h1>Connexion admin</h1>

    <?php if ($error): ?>
        <div class="message error"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="panel">
        <form method="post" action="/admin/password">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required autofocus>
            <button type="submit">Valider</button>
            <a class="button secondary" href="/">Annuler</a>
            <p class="auth-help">Mot de passe admin : <strong>admin123</strong></p>
        </form>
    </div>
</div>
</body>
</html>
