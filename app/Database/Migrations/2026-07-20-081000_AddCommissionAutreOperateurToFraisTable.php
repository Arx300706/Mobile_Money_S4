<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCommissionAutreOperateurToFraisTable extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('frais');

        if (! in_array('commission_autre_operateur', $fields, true)) {
            $this->forge->addColumn('frais', [
                'commission_autre_operateur' => [
                    'type'    => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                    'null'    => false,
                ],
            ]);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('frais');

        if (in_array('commission_autre_operateur', $fields, true)) {
            $this->forge->dropColumn('frais', 'commission_autre_operateur');
        }
    }
}
