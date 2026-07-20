<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Administration') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            color: #20242a;
            background: #f4f6f8;
        }

        .topbar {
            background: #263238;
            color: #fff;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
        }

        .topbar a {
            color: #fff;
            text-decoration: none;
            margin-right: 14px;
        }

        .page {
            max-width: 1100px;
            margin: 26px auto;
            padding: 0 18px;
        }

        .panel {
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 6px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
        }

        .stat {
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 6px;
            padding: 16px;
        }

        .stat strong {
            display: block;
            font-size: 28px;
            margin-top: 6px;
        }

        h1 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #d9dee3;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eef2f4;
        }

        input {
            width: 100%;
            max-width: 430px;
            padding: 9px;
            border: 1px solid #b8c0c7;
            border-radius: 4px;
            margin-bottom: 14px;
            box-sizing: border-box;
        }

        select {
            width: 100%;
            max-width: 430px;
            padding: 9px;
            border: 1px solid #b8c0c7;
            border-radius: 4px;
            margin-bottom: 14px;
            box-sizing: border-box;
            background: #fff;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .button,
        button {
            display: inline-block;
            padding: 9px 13px;
            border: 0;
            border-radius: 4px;
            background: #0b62a3;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .button.secondary,
        button.secondary {
            background: #58646f;
        }

        .button.danger,
        button.danger {
            background: #a83232;
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

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
            align-items: end;
        }

        .inline-form {
            display: inline;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
<div class="topbar">
    <nav>
        <a href="/test">Test DB</a>
        <a href="/operateur">Operateurs</a>
        <a href="/TypeOperation">Types operations</a>
        <a href="/SituationGain">Situation gains</a>
    </nav>
    <form method="post" action="/logout">
        <button class="secondary" type="submit">Deconnexion</button>
    </form>
</div>
<main class="page">
