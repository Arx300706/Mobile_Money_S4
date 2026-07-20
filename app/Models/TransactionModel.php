<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_type_operations',
        'montant',
        'date',
        'id_compte_client',
        'id_compte_destinataire',
        'montant_frais',
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
        'id_type_operations'      => 'required|is_natural_no_zero',
        'montant'                 => 'required|decimal|greater_than[0]',
        'date'                    => 'permit_empty|valid_date[Y-m-d]',
        'id_compte_client'        => 'required|is_natural_no_zero',
        'id_compte_destinataire'  => 'permit_empty|is_natural_no_zero',
        'montant_frais'           => 'permit_empty|decimal|greater_than_equal_to[0]',
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
        return $this->select('transaction.*, type_operations.nom AS type_operation, client.nom AS client_nom, client.prenom AS client_prenom, destinataire.id AS compte_destinataire_id')
            ->join('type_operations', 'type_operations.id = transaction.id_type_operations')
            ->join('compte_client', 'compte_client.id = transaction.id_compte_client')
            ->join('client', 'client.id = compte_client.id_client')
            ->join('compte_client AS destinataire', 'destinataire.id = transaction.id_compte_destinataire', 'left');
    }
}
