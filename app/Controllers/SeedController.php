<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SeedController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $db->transStart();

        $tables = ['achat', 'client', 'caisse', 'produit'];

        foreach ($tables as $table) {
            if ($db->tableExists($table)) {
                $db->table($table)->delete();
            }
        }

        $db->query("DELETE FROM sqlite_sequence WHERE name IN ('achat', 'client', 'caisse', 'produit')");

        $db->query("
            INSERT INTO produit (designation, prix, stock) VALUES
            ('Riz 1kg', 3000, 50),
            ('Sucre', 4500, 30),
            ('Lait 1L', 2500, 20),
            ('Huile 1L', 6000, 15),
            ('Pates', 2000, 40)
        ");

        $db->query("
            INSERT INTO caisse (montant_total, date) VALUES
            (10500, '2026-06-17 10:00:00'),
            (15000, '2026-06-17 12:00:00')
        ");

        $db->transComplete();

        if ($db->transStatus() === false) {
            return 'Erreur pendant la reinitialisation des donnees.';
        }

        return 'Donnees reinitialisees : 5 produits et 2 caisses.';
    }
}
