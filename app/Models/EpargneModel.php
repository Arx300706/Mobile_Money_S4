<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_compte_client', 'pourcentage'];

    public function fingByIdCompteClient(int $compte): ?array
    {
        return $this->where('id_compte_client', $compte)->first();
    }
}