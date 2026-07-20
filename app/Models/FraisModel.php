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
        'type_frais',
        'montant_frais',
        'commission_autre_operateur',
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
        'type_frais'          => 'required|in_list[fixe,pourcentage]',
        'montant_frais'       => 'required|numeric|greater_than_equal_to[0]',
        'commission_autre_operateur' => 'permit_empty|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function findForAmount(int $operateurId, int $typeOperationId, float $montant): ?array
    {
        $operateurId = $this->resolveBaremeOperateurId($operateurId);

        return $this->where('id_operateur', $operateurId)
            ->where('id_type_operations', $typeOperationId)
            ->where('tranche_min <=', $montant)
            ->where('tranche_max >=', $montant)
            ->first();
    }

    public function calculerFrais(array $bareme, float $montant): float
    {
        if (($bareme['type_frais'] ?? 'fixe') === 'pourcentage') {
            return $montant * (float) $bareme['montant_frais'] / 100;
        }

        return (float) $bareme['montant_frais'];
    }

    public function calculerCommissionAutreOperateur(array $bareme, float $montant): float
    {
        return $montant * (float) ($bareme['commission_autre_operateur'] ?? 0) / 100;
    }

    public function withDetails()
    {
        return $this->select('frais.*, operateur.nom AS operateur_nom, type_operations.nom AS type_operation')
            ->join('operateur', 'operateur.id = frais.id_operateur')
            ->join('type_operations', 'type_operations.id = frais.id_type_operations')
            ->orderBy('operateur.id', 'ASC')
            ->orderBy('type_operations.id', 'ASC')
            ->orderBy('frais.tranche_min', 'ASC');
    }

    public function hasOverlappingTranche(
        int $operateurId,
        int $typeOperationId,
        int $trancheMin,
        int $trancheMax,
        ?int $ignoreId = null
    ): bool {
        $operateurId = $this->resolveBaremeOperateurId($operateurId);

        $builder = $this->where('id_operateur', $operateurId)
            ->where('id_type_operations', $typeOperationId)
            ->where('tranche_min <=', $trancheMax)
            ->where('tranche_max >=', $trancheMin);

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->first() !== null;
    }

    private function resolveBaremeOperateurId(int $operateurId): int
    {
        $operateur = $this->db->table('operateur')
            ->where('id', $operateurId)
            ->get()
            ->getRowArray();

        if (($operateur['nom'] ?? '') !== 'OP') {
            return $operateurId;
        }

        $primaryOp = $this->db->table('operateur')
            ->where('nom', 'OP')
            ->orderBy('id', 'ASC')
            ->get()
            ->getRowArray();

        return $primaryOp ? (int) $primaryOp['id'] : $operateurId;
    }
}
