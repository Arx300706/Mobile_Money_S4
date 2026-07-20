<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page introuvable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #20242a;
            background: #f4f6f8;
        }

        .panel {
            max-width: 720px;
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 6px;
            padding: 22px;
        }

        a {
            color: #0b62a3;
        }
    </style>
</head>
<body>
<div class="panel">
    <h1>Page introuvable</h1>
    <p><?= esc($message ?? 'La page demandee est introuvable.') ?></p>
    <p><a href="/operateur">Retour aux operateurs</a></p>
</div>
</body>
</html>
