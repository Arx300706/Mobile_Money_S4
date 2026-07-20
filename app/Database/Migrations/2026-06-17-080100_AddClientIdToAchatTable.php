<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientIdToAchatTable extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('client_id', 'achat')) {
            return;
        }

        $this->forge->addColumn('achat', [
            'client_id' => [
                'type' => 'INTEGER',
                'null' => true,
                'after' => 'caisse_id',
            ],
        ]);
    }

    public function down()
    {
        if (!$this->db->fieldExists('client_id', 'achat')) {
            return;
        }

        $this->forge->dropColumn('achat', 'client_id');
    }
}
