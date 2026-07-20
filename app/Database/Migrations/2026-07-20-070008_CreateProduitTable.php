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
                'null' => false,
            ],
            'prix' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'stock' => [
                'type' => 'INTEGER',
                'null' => false,
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produit', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'date' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('client', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'montant_total' => [
                'type' => 'REAL',
                'null' => false,
                'default' => 0,
            ],
            'date' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('caisse', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'produit_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'caisse_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'client_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'quantite' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'prix_unitaire' => [
                'type' => 'REAL',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('produit_id');
        $this->forge->addKey('caisse_id');
        $this->forge->addKey('client_id');
        $this->forge->addForeignKey('produit_id', 'produit', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('caisse_id', 'caisse', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('client_id', 'client', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('achat', true);
    }

    public function down()
    {
        $this->forge->dropTable('achat', true);
        $this->forge->dropTable('caisse', true);
        $this->forge->dropTable('client', true);
        $this->forge->dropTable('produit', true);
    }
}
