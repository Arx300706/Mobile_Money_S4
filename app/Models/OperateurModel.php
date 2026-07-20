<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'prefixe',
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
        'nom'     => 'required|max_length[100]',
        'prefixe' => 'required|integer|is_unique[operateur.prefixe,id,{id}]',
    ];
    protected $validationMessages   = [
        'prefixe' => [
            'is_unique' => 'Ce prefixe est deja utilise.',
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

    public function findByPrefixe(int $prefixe): ?array
    {
        return $this->where('prefixe', $prefixe)->first();
    }

    public function findByTelephone(string $telephone): ?array
    {
        $numero = preg_replace('/\D+/', '', $telephone);

        if (str_starts_with($numero, '0')) {
            $numero = substr($numero, 1);
        }

        $prefixe = substr($numero, 0, 2);

        if ($prefixe === '') {
            return null;
        }

        return $this->findByPrefixe((int) $prefixe);
    }
}
