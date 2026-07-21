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
        'montant'                 => 'required|numeric|greater_than[0]',
        'date'                    => 'permit_empty|valid_date[Y-m-d]',
        'id_compte_client'        => 'required|is_natural_no_zero',
        'id_compte_destinataire'  => 'permit_empty|is_natural_no_zero',
        'montant_frais'           => 'permit_empty|numeric|greater_than_equal_to[0]',
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
        return $this->select('transaction.*, type_operations.nom AS type_operation, client.nom AS client_nom, client.prenom AS client_prenom, destinataire.id AS compte_destinataire_id, client_destinataire.nom AS destinataire_nom, client_destinataire.prenom AS destinataire_prenom')
            ->join('type_operations', 'type_operations.id = transaction.id_type_operations')
            ->join('compte_client', 'compte_client.id = transaction.id_compte_client')
            ->join('client', 'client.id = compte_client.id_client')
            ->join('compte_client AS destinataire', 'destinataire.id = transaction.id_compte_destinataire', 'left')
            ->join('client AS client_destinataire', 'client_destinataire.id = destinataire.id_client', 'left');
    }

    public function findByCompte(int $compteId): array
    {
        return $this->withDetails()
            ->groupStart()
                ->where('transaction.id_compte_client', $compteId)
                ->orWhere('transaction.id_compte_destinataire', $compteId)
            ->groupEnd()
            ->orderBy('transaction.date', 'DESC')
            ->orderBy('transaction.id', 'DESC')
            ->findAll();
    }

    // public function gainsSummary(?string $dateDebut = null, ?string $dateFin = null, ?int $typeOperationId = null): array
    // {
    //     $builder = $this->db->table('"transaction" AS tr')
    //         ->select('type_operations.id AS type_id, type_operations.nom AS type_operation, COUNT(tr.id) AS nombre_operations, SUM(tr.montant) AS montant_total, SUM(tr.montant_frais) AS gain_total')
    //         ->join('type_operations', 'type_operations.id = tr.id_type_operations')
    //         ->whereIn('type_operations.nom', ['Retrait', 'Transfert'])
    //         ->groupBy('type_operations.id, type_operations.nom')
    //         ->orderBy('type_operations.id', 'ASC');

    //     $this->applyGainsFilters($builder, $dateDebut, $dateFin, $typeOperationId);

    //     return $builder->get()->getResultArray();
    // }

    public function gainsSummary(?string $dateDebut = null, ?string $dateFin = null, ?int $typeOperationId = null): array
    {
        $builder = $this->db->table('"transaction" AS tr')
            ->select("CASE WHEN type_operations.nom = 'Transfert' AND operateur_destinataire.nom != 'OP' THEN 'Autres Operateurs' ELSE 'OP' END AS categorie_operateur", false)
            ->select('type_operations.id AS type_id, type_operations.nom AS type_operation, COUNT(tr.id) AS nombre_operations, SUM(tr.montant) AS montant_total, SUM(tr.montant_frais) AS gain_total')
            ->join('type_operations', 'type_operations.id = tr.id_type_operations')
            ->join('compte_client AS compte_source', 'compte_source.id = tr.id_compte_client')
            ->join('client AS client_source', 'client_source.id = compte_source.id_client')
            ->join('operateur AS operateur_source', "client_source.telephone LIKE ('0' || operateur_source.prefixe || '%')", 'inner', false)
            ->join('compte_client AS compte_destinataire', 'compte_destinataire.id = tr.id_compte_destinataire', 'left')
            ->join('client AS client_destinataire', 'client_destinataire.id = compte_destinataire.id_client', 'left')
            ->join('operateur AS operateur_destinataire', "client_destinataire.telephone LIKE ('0' || operateur_destinataire.prefixe || '%')", 'left', false)
            ->groupStart()
                ->groupStart()
                    ->where('type_operations.nom', 'Retrait')
                    ->where('operateur_source.nom', 'OP')
                ->groupEnd()
                ->orWhere('type_operations.nom', 'Transfert')
            ->groupEnd()
            ->groupBy('categorie_operateur, type_operations.id, type_operations.nom')
            ->orderBy('categorie_operateur', 'DESC')
            ->orderBy('type_operations.id', 'ASC');

        $this->applyGainsFilters($builder, $dateDebut, $dateFin, $typeOperationId);

        return $builder->get()->getResultArray();
    }

    // public function gainsDetails(?string $dateDebut = null, ?string $dateFin = null, ?int $typeOperationId = null): array
    // {
    //     $builder = $this->db->table('"transaction" AS tr')
    //         ->select('tr.*, type_operations.nom AS type_operation, client.nom AS client_nom, client.prenom AS client_prenom, client.telephone')
    //         ->join('type_operations', 'type_operations.id = tr.id_type_operations')
    //         ->join('compte_client', 'compte_client.id = tr.id_compte_client')
    //         ->join('client', 'client.id = compte_client.id_client')
    //         ->whereIn('type_operations.nom', ['Retrait', 'Transfert'])
    //         ->orderBy('tr.date', 'DESC')
    //         ->orderBy('tr.id', 'DESC');

    //     $this->applyGainsFilters($builder, $dateDebut, $dateFin, $typeOperationId);

    //     return $builder->get()->getResultArray();
    // }

    public function gainsDetails(?string $dateDebut = null, ?string $dateFin = null, ?int $typeOperationId = null): array
    {
        $builder = $this->db->table('"transaction" AS tr')
            ->select("CASE WHEN type_operations.nom = 'Transfert' AND operateur_destinataire.nom != 'OP' THEN 'Autres Operateurs' ELSE 'OP' END AS categorie_operateur", false)
            ->select('tr.*, type_operations.nom AS type_operation, client_source.nom AS client_nom, client_source.prenom AS client_prenom, client_source.telephone')
            ->join('type_operations', 'type_operations.id = tr.id_type_operations')
            ->join('compte_client AS compte_source', 'compte_source.id = tr.id_compte_client')
            ->join('client AS client_source', 'client_source.id = compte_source.id_client')
            ->join('operateur AS operateur_source', "client_source.telephone LIKE ('0' || operateur_source.prefixe || '%')", 'inner', false)
            ->join('compte_client AS compte_destinataire', 'compte_destinataire.id = tr.id_compte_destinataire', 'left')
            ->join('client AS client_destinataire', 'client_destinataire.id = compte_destinataire.id_client', 'left')
            ->join('operateur AS operateur_destinataire', "client_destinataire.telephone LIKE ('0' || operateur_destinataire.prefixe || '%')", 'left', false)
            ->groupStart()
                ->groupStart()
                    ->where('type_operations.nom', 'Retrait')
                    ->where('operateur_source.nom', 'OP')
                ->groupEnd()
                ->orWhere('type_operations.nom', 'Transfert')
            ->groupEnd()
            ->orderBy('tr.date', 'DESC')
            ->orderBy('tr.id', 'DESC');

        $this->applyGainsFilters($builder, $dateDebut, $dateFin, $typeOperationId);

        return $builder->get()->getResultArray();
    }

    public function montantsAEnvoyerSummary(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->db->table('"transaction" AS tr')
            ->select('operateur_destinataire.nom AS operateur_destinataire, COUNT(tr.id) AS nombre_transferts, SUM(tr.montant) AS montant_total, SUM(tr.montant_frais) AS gain_total')
            ->join('type_operations', 'type_operations.id = tr.id_type_operations')
            ->join('compte_client AS compte_destinataire', 'compte_destinataire.id = tr.id_compte_destinataire')
            ->join('client AS client_destinataire', 'client_destinataire.id = compte_destinataire.id_client')
            ->join('operateur AS operateur_destinataire', "client_destinataire.telephone LIKE ('0' || operateur_destinataire.prefixe || '%')", 'inner', false)
            ->where('type_operations.nom', 'Transfert')
            ->groupBy('operateur_destinataire.nom')
            ->orderBy('operateur_destinataire.nom', 'ASC');

        $this->applyDateFilters($builder, $dateDebut, $dateFin);

        return $builder->get()->getResultArray();
    }

    public function montantsAEnvoyerDetails(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->db->table('"transaction" AS tr')
            ->select('operateur_destinataire.nom AS operateur_destinataire, tr.date, tr.montant, client_source.nom AS source_nom, client_source.prenom AS source_prenom, client_source.telephone AS source_telephone, client_destinataire.nom AS destinataire_nom, client_destinataire.prenom AS destinataire_prenom, client_destinataire.telephone AS destinataire_telephone')
            ->join('type_operations', 'type_operations.id = tr.id_type_operations')
            ->join('compte_client AS compte_source', 'compte_source.id = tr.id_compte_client')
            ->join('client AS client_source', 'client_source.id = compte_source.id_client')
            ->join('compte_client AS compte_destinataire', 'compte_destinataire.id = tr.id_compte_destinataire')
            ->join('client AS client_destinataire', 'client_destinataire.id = compte_destinataire.id_client')
            ->join('operateur AS operateur_destinataire', "client_destinataire.telephone LIKE ('0' || operateur_destinataire.prefixe || '%')", 'inner', false)
            ->where('type_operations.nom', 'Transfert')
            ->orderBy('tr.date', 'DESC')
            ->orderBy('tr.id', 'DESC');

        $this->applyDateFilters($builder, $dateDebut, $dateFin);

        return $builder->get()->getResultArray();
    }

    private function applyGainsFilters($builder, ?string $dateDebut, ?string $dateFin, ?int $typeOperationId): void
    {
        if ($dateDebut) {
            $builder->where('tr.date >=', $dateDebut);
        }

        if ($dateFin) {
            $builder->where('tr.date <=', $dateFin);
        }

        if ($typeOperationId !== null && $typeOperationId > 0) {
            $builder->where('type_operations.id', $typeOperationId);
        }
    }

    private function applyDateFilters($builder, ?string $dateDebut, ?string $dateFin): void
    {
        if ($dateDebut) {
            $builder->where('tr.date >=', $dateDebut);
        }

        if ($dateFin) {
            $builder->where('tr.date <=', $dateFin);
        }
    }
}
