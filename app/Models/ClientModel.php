<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'prenom',
        'date_naissance',
        'adresse',
        'email',
        'telephone',
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
        'id'             => 'permit_empty|is_natural_no_zero',
        'nom'            => 'required|max_length[100]',
        'prenom'         => 'required|max_length[100]',
        'date_naissance' => 'required|valid_date[Y-m-d]',
        'adresse'        => 'required|max_length[255]',
        'email'          => 'required|valid_email|max_length[150]|is_unique[client.email,id,{id}]',
        'telephone'      => 'required|max_length[20]|is_unique[client.telephone,id,{id}]',
    ];
    protected $validationMessages   = [
        'email' => [
            'is_unique' => 'Cette adresse email est deja utilisee.',
        ],
        'telephone' => [
            'is_unique' => 'Ce telephone est deja utilise.',
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

    public function withCompte()
    {
        return $this->select('client.*, compte_client.id AS compte_id, compte_client.date_creation, compte_client.solde')
            ->join('compte_client', 'compte_client.id_client = client.id', 'left');
    }
}
