<!DOCTYPE html>
<html>
<head>
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
            <label>Numero de telephone :</label><br>
            <input type="text" name="telephone" placeholder="03XXXXXXXX" required autofocus>
            <button type="submit">Acceder au compte</button>
        </form>
    </div>

    <p>Pour l'administration, entrez <strong>admin</strong> comme numero.</p>
    <p>Pour le client, entrez un numero de telephone <strong>0341234567</strong></p>
</div>

</body>
</html>
