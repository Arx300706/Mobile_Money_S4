<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table            = 'achat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'produit_id',
        'caisse_id',
        'client_id',
        'quantite',
        'prix_unitaire',
    ];

    public function findByCaisseAndClient(int $caisseId, int $clientId): array
    {
        return $this->select('achat.*, produit.designation, produit.prix, produit.stock, client.nom AS client_nom')
            ->join('produit', 'produit.id = achat.produit_id')
            ->join('client', 'client.id = achat.client_id', 'left')
            ->where('achat.caisse_id', $caisseId)
            ->where('achat.client_id', $clientId)
            ->orderBy('achat.id', 'DESC')
            ->findAll();
    }
}
