<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table            = 'frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_operateur',
        'id_type_operations',
        'tranche_min',
        'tranche_max',
        'montant_frais',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [
        'id_operateur'        => 'required|is_natural_no_zero',
        'id_type_operations'  => 'required|is_natural_no_zero',
        'tranche_min'         => 'required|integer|greater_than_equal_to[0]',
        'tranche_max'         => 'required|integer|greater_than_equal_to[0]',
        'montant_frais'       => 'required|decimal|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['verifierTranche'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['verifierTranche'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function verifierTranche(array $data): array
    {
        if (! isset($data['data']['tranche_min'], $data['data']['tranche_max'])) {
            return $data;
        }

        if ((int) $data['data']['tranche_max'] < (int) $data['data']['tranche_min']) {
            $this->errors['tranche_max'] = 'La tranche maximale doit etre superieure ou egale a la tranche minimale.';
        }

        return $data;
    }

    public function findForAmount(int $operateurId, int $typeOperationId, float $montant): ?array
    {
        return $this->where('id_operateur', $operateurId)
            ->where('id_type_operations', $typeOperationId)
            ->where('tranche_min <=', $montant)
            ->where('tranche_max >=', $montant)
            ->first();
    }

    public function withDetails()
    {
        return $this->select('frais.*, operateur.nom AS operateur_nom, type_operations.nom AS type_operation')
            ->join('operateur', 'operateur.id = frais.id_operateur')
            ->join('type_operations', 'type_operations.id = frais.id_type_operations');
    }
}
