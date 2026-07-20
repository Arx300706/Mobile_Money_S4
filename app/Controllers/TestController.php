<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $tables = [
            'OPERATEUR' => 'operateur',
            'TYPE OPERATIONS' => 'type_operations',
            'FRAIS' => 'frais',
            'CLIENT' => 'client',
            'COMPTE CLIENT' => 'compte_client',
            'TRANSACTION' => '"transaction"',
            'HISTORIQUE TRANSACTION' => 'historique_transaction',
        ];

        echo "<pre>";

        foreach ($tables as $title => $table) {
            echo "\n" . $title . "\n";
            echo str_repeat('-', strlen($title)) . "\n";

            try {
                $rows = $db->query("SELECT * FROM {$table}")->getResult();
                print_r($rows);
            } catch (\Throwable $e) {
                echo 'Erreur: ' . $e->getMessage() . "\n";
            }
        }

        echo "</pre>";
    }
}
