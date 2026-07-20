<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduitTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'designation' => [
                'type' => 'TEXT',
            ],
            'prix' => [
                'type' => 'REAL',
            ],
            'stock' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('produit', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('produit', true);
    }
}
