<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCaisseTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'montant_total' => [
                'type' => 'REAL',
                'default' => 0,
            ],
            'date' => [
                'type' => 'TEXT',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('caisse', true);
    }

    public function down()
    {
        $this->forge->dropTable('caisse', true);
    }
}
