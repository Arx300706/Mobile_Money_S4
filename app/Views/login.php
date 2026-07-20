<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/style/styles.css">
</head>
<body class="auth-page">
<div class="page">
    <h1>Connexion Mobile Money</h1>

    <?php if ($success): ?>
        <div class="message success"><?= esc($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="panel">
        <h2>Connexion client</h2>
        <form method="post" action="/login/client">
            <label for="telephone">Numero de telephone</label>
            <input id="telephone" type="text" name="telephone" placeholder="03XXXXXXXX" required autofocus>
            <button type="submit">Acceder au compte</button>
        </form>
    </div>

    <p class="auth-help">Admin : entrez <strong>admin</strong> comme numero.</p>
    <p class="auth-help">Client test : <strong>0341234567</strong></p>
</div>

</body>
</html>
