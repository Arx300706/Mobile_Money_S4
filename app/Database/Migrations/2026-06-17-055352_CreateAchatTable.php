<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAchatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type' => 'INTEGER',
            ],
            'caisse_id' => [
                'type' => 'INTEGER',
            ],
            'quantite' => [
                'type' => 'INTEGER',
            ],
            'prix_unitaire' => [
                'type' => 'REAL',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('achat', true);
    }

    public function down()
    {
        $this->forge->dropTable('achat', true);
    }
}
