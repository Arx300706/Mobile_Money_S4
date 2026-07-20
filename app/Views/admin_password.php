<!DOCTYPE html>
<html>
<head>
    <title>Mot de passe admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f7f8;
            color: #222;
        }

        .page {
            max-width: 460px;
            margin: 0 auto;
        }

        .panel {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
        }

        input {
            width: 100%;
            padding: 9px;
            border: 1px solid #bbb;
            border-radius: 4px;
            margin-bottom: 14px;
            box-sizing: border-box;
        }

        button,
        .button {
            display: inline-block;
            padding: 10px 16px;
            border: 0;
            border-radius: 4px;
            background: #0b62a3;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .button.secondary {
            background: #58646f;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
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
        </form>
    </div>
</div>
</body>
</html>
