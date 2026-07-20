<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteClientModel extends Model
{
    protected $table            = 'compte_client';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_client',
        'date_creation',
        'solde',
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
        'id'            => 'permit_empty|is_natural_no_zero',
        'id_client'     => 'required|is_natural_no_zero|is_unique[compte_client.id_client,id,{id}]',
        'date_creation' => 'permit_empty|valid_date[Y-m-d]',
        'solde'         => 'permit_empty|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [
        'id_client' => [
            'is_unique' => 'Ce client possede deja un compte.',
        ],
    ];
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

    public function withClient()
    {
        return $this->select('compte_client.*, client.nom, client.prenom, client.telephone')
            ->join('client', 'client.id = compte_client.id_client');
    }

    public function findByClientId(int $clientId): ?array
    {
        return $this->where('id_client', $clientId)->first();
    }

    public function findByTelephone(string $telephone): ?array
    {
        return $this->withClient()
            ->where('client.telephone', $telephone)
            ->first();
    }
}
