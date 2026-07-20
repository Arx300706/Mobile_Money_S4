<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Administration') ?></title>
    <link rel="stylesheet" href="/style/styles.css">
</head>
<body>
<div class="topbar">
    <nav>
        <?php if (session()->get('role') === 'admin'): ?>
            <a href="/test">Test DB</a>
            <a href="/operateur">Operateurs</a>
            <a href="/TypeOperation">Types operations</a>
            <a href="/SituationGain">Situation gains</a>
            <a href="/SituationClient">Situation clients</a>
        <?php elseif (session()->get('role') === 'client'): ?>
            <a href="/compte">Mon compte</a>
        <?php endif; ?>
    </nav>
    <form method="post" action="/logout">
        <button class="secondary" type="submit">Deconnexion</button>
    </form>
</div>
<main class="page">
