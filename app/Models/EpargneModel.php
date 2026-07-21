<?php

namespace App\Models;

use CodeIgniter\Model;

class   EpargneModel extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_compte_client',
        'pourcentage',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [
    //     'id'  => 'permit_empty|is_natural_no_zero',
    //     '' => 'required|max_length[50]|is_unique[type_operations.nom,id,{id}]',
    // ];
    // protected $validationMessages   = [
    //     'nom' => [
    //         'is_unique' => 'Ce type d operation existe deja.',
    //     ],
    // ];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

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

    public function fingByIdCompteClient(int $compte): ?array
    {
        return $this->where('id_compte_client', $compte)->first();
    }
}
