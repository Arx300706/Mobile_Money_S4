<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionFraisModel extends Model
{
    protected $table            = 'promotion_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'id_type_operations',
        'cible',
        'type_promotion',
        'valeur',
        'actif',
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

    protected $validationRules = [
        'nom'                => 'required|max_length[100]',
        'id_type_operations' => 'required|is_natural_no_zero',
        'cible'              => 'required|in_list[meme_operateur]',
        'type_promotion'    => 'required|in_list[fixe,pourcentage]',
        'valeur'             => 'required|numeric|greater_than_equal_to[0]',
        'actif'              => 'permit_empty|in_list[0,1]',
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

    public function findActiveForTransfertMemeOperateur(int $typeOperationId): ?array
    {
        return $this->where('id_type_operations', $typeOperationId)
            ->where('cible', 'meme_operateur')
            ->where('actif', 1)
            ->first();
    }

    public function calculerReduction(array $promotion, float $frais): float
    {
        if ($frais <= 0) {
            return 0.0;
        }

        if (($promotion['type_promotion'] ?? 'fixe') === 'pourcentage') {
            $reduction = $frais * (float) $promotion['valeur'] / 100;
        } else {
            $reduction = (float) $promotion['valeur'];
        }

        return min($frais, max(0.0, $reduction));
    }
}