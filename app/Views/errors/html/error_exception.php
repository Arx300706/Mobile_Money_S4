<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #20242a;
            background: #f4f6f8;
        }

        .panel {
            max-width: 900px;
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 6px;
            padding: 22px;
        }

        pre {
            overflow: auto;
            background: #f0f2f4;
            padding: 14px;
        }
    </style>
</head>
<body>
<div class="panel">
    <h1>Erreur application</h1>
    <p><?= esc($message ?? 'Une erreur est survenue.') ?></p>

    <?php if (isset($exception) && ENVIRONMENT !== 'production'): ?>
        <h2>Detail</h2>
        <pre><?= esc($exception::class . ': ' . $exception->getMessage()) ?></pre>
    <?php endif; ?>
</div>
</body>
</html>
