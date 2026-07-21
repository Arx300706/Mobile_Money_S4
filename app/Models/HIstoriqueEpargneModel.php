<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueEpargneModel extends Model
{
    protected $table            = 'historique_epargne';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_compte_client',
        'valeur_epargne',
        'valeur_solde',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates (Tu peux activer les timestamps si tu ajoutes created_at à ta table plus tard)
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_compte_client' => 'required|is_natural_no_zero',
        'valeur_epargne'    => 'required|decimal',
        'valeur_solde'      => 'required|decimal',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Récupère tout l'historique d'épargne pour un compte client donné.
     */
    public function findByIdCompteClient(int $compteId): array
    {
        return $this->where('id_compte_client', $compteId)
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }
}