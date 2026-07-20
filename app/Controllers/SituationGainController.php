<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TypeOperationsModel;

class SituationGainController extends BaseController
{
    public function index()
    {
        $dateDebut = $this->cleanDate($this->request->getGet('date_debut'));
        $dateFin = $this->cleanDate($this->request->getGet('date_fin'));
        $typeOperationId = (int) ($this->request->getGet('type_operation_id') ?? 0);
        $transactionModel = new TransactionModel();
        $summary = $transactionModel->gainsSummary($dateDebut, $dateFin, $typeOperationId);
        $details = $transactionModel->gainsDetails($dateDebut, $dateFin, $typeOperationId);

        return view('situation/SituationGain', [
            'summary' => $summary,
            'details' => $details,
            'types' => (new TypeOperationsModel())
                ->whereIn('nom', ['Retrait', 'Transfert'])
                ->orderBy('id', 'ASC')
                ->findAll(),
            'filters' => [
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'type_operation_id' => $typeOperationId,
            ],
            'totalGain' => array_sum(array_map(static fn (array $row): float => (float) $row['gain_total'], $summary)),
            'totalMontant' => array_sum(array_map(static fn (array $row): float => (float) $row['montant_total'], $summary)),
            'totalOperations' => array_sum(array_map(static fn (array $row): int => (int) $row['nombre_operations'], $summary)),
        ]);
    }

    private function cleanDate($date): ?string
    {
        $date = trim((string) $date);

        if ($date === '') {
            return null;
        }

        $parsed = date_create_from_format('Y-m-d', $date);

        if (! $parsed) {
            return null;
        }

        return $parsed->format('Y-m-d');
    }
}
