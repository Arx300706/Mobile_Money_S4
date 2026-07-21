<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionFraisTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('promotion_frais')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INTEGER',
                    'auto_increment' => true,
                ],
                'nom' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'id_type_operations' => [
                    'type' => 'INTEGER',
                    'null' => false,
                ],
                'cible' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'meme_operateur',
                    'null'       => false,
                ],
                'type_promotion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'pourcentage',
                    'null'       => false,
                ],
                'valeur' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => 0,
                    'null'       => false,
                ],
                'actif' => [
                    'type'       => 'INTEGER',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('id_type_operations', 'type_operations', 'id');
            $this->forge->createTable('promotion_frais');
        }

        $typeTransfert = $this->db->table('type_operations')
            ->where('nom', 'Transfert')
            ->get()
            ->getRowArray();

        if ($typeTransfert) {
            $exists = $this->db->table('promotion_frais')
                ->where('cible', 'meme_operateur')
                ->where('id_type_operations', (int) $typeTransfert['id'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('promotion_frais')->insert([
                    'nom' => 'Promotion frais transfert meme operateur',
                    'id_type_operations' => (int) $typeTransfert['id'],
                    'cible' => 'meme_operateur',
                    'type_promotion' => 'pourcentage',
                    'valeur' => 0,
                    'actif' => 0,
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('promotion_frais')) {
            $this->forge->dropTable('promotion_frais');
        }
    }
}
