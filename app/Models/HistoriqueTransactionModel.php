<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueTransactionModel extends Model
{
    protected $table            = 'historique_transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_transaction',
        'date',
        'montant',
        'id_type_operations',
        'solde_avant',
        'solde_apres',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_transaction'     => 'required|is_natural_no_zero',
        'date'               => 'permit_empty|valid_date[Y-m-d]',
        'montant'            => 'required|numeric|greater_than[0]',
        'id_type_operations' => 'required|is_natural_no_zero',
        'solde_avant'        => 'required|numeric|greater_than_equal_to[0]',
        'solde_apres'        => 'required|numeric|greater_than_equal_to[0]',
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

    public function withDetails()
    {
        return $this->select('historique_transaction.*, type_operations.nom AS type_operation, tr.id_compte_client, tr.id_compte_destinataire')
            ->join('type_operations', 'type_operations.id = historique_transaction.id_type_operations')
            ->join('"transaction" AS tr', 'tr.id = historique_transaction.id_transaction');
    }

    public function findByCompte(int $compteId): array
    {
        return $this->withDetails()
            ->groupStart()
                ->where('tr.id_compte_client', $compteId)
                ->orWhere('tr.id_compte_destinataire', $compteId)
            ->groupEnd()
            ->orderBy('historique_transaction.date', 'DESC')
            ->orderBy('historique_transaction.id', 'DESC')
            ->findAll();
    }
}
