<!DOCTYPE html>
<html>
<head>
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
            <label>Mot de passe :</label><br>
            <input type="password" name="password" required autofocus>
            <button type="submit">Valider</button>
            <a class="button secondary" href="/">Annuler</a>
             <p>Pour l'administration, entrez <strong>admin123</strong> comme mot de passe.</p>
        </form>
    </div>
</div>
</body>
</html>
