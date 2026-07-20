<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitModel extends Model
{
    protected $table            = 'produit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'designation',
        'prix',
        'stock',
    ];
}
