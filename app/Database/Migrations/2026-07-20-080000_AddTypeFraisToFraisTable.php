<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeFraisToFraisTable extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('frais');

        if (! in_array('type_frais', $fields, true)) {
            $this->forge->addColumn('frais', [
                'type_frais' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'fixe',
                    'null'       => false,
                ],
            ]);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('frais');

        if (in_array('type_frais', $fields, true)) {
            $this->forge->dropColumn('frais', 'type_frais');
        }
    }
}
