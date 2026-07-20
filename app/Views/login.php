<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
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

        button {
            padding: 10px 16px;
            border: 0;
            border-radius: 4px;
            background: #0b62a3;
            color: #fff;
            cursor: pointer;
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
    <h1>Connexion supermarche</h1>

    <?php if ($success): ?>
        <div class="message success"><?= esc($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="panel">
        <h2>Connexion client</h2>
        <form method="post" action="/login/client">
            <label>Nom du client :</label><br>
            <input type="text" name="client" required>
            <button type="submit">Entrer comme client</button>
        </form>
    </div>
</div>

</body>
</html>
