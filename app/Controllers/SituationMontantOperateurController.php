<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class SituationMontantOperateurController extends BaseController
{
    public function index()
    {
        $dateDebut = $this->cleanDate($this->request->getGet('date_debut'));
        $dateFin = $this->cleanDate($this->request->getGet('date_fin'));
        $transactionModel = new TransactionModel();
        $summary = $transactionModel->montantsAEnvoyerSummary($dateDebut, $dateFin);
        $details = $transactionModel->montantsAEnvoyerDetails($dateDebut, $dateFin);

        return view('situation/SituationMontantOperateur', [
            'summary' => $summary,
            'details' => $details,
            'filters' => [
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
            ],
            'totalMontant' => array_sum(array_map(static fn (array $row): float => (float) $row['montant_total'], $summary)),
            'totalTransferts' => array_sum(array_map(static fn (array $row): int => (int) $row['nombre_transferts'], $summary)),
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
