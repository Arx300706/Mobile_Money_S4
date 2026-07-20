<?php

namespace App\Controllers;

use App\Models\CompteClientModel;
use App\Models\FraisModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\OperateurModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationsModel;

class CompteClientController extends BaseController
{
    public function index()
    {
        $compte = $this->currentCompte();

        if (! $compte) {
            return redirect()->to('/')->with('error', 'Compte client introuvable.');
        }

        return view('client/compte', [
            'compte' => $compte,
            'historiques' => (new HistoriqueTransactionModel())->findByCompte((int) $compte['id']),
            'success' => session()->getFlashdata('success'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function depot()
    {
        return $this->operationSimple('Depot');
    }

    public function retrait()
    {
        return $this->operationSimple('Retrait');
    }

    public function transfert()
    {
        $compteSource = $this->currentCompte();

        if (! $compteSource) {
            return redirect()->to('/')->with('error', 'Compte client introuvable.');
        }

        $montant = (float) $this->request->getPost('montant');
        $telephoneDestinataire = trim((string) $this->request->getPost('telephone_destinataire'));
        $compteDestinataire = (new CompteClientModel())->findByTelephone($telephoneDestinataire);

        if ($montant <= 0) {
            return redirect()->to('/compte')->with('errors', ['Le montant du transfert doit etre superieur a 0.']);
        }

        if (! $compteDestinataire) {
            return redirect()->to('/compte')->with('errors', ['Compte destinataire introuvable.']);
        }

        if ((int) $compteDestinataire['id'] === (int) $compteSource['id']) {
            return redirect()->to('/compte')->with('errors', ['Impossible de transferer vers votre propre compte.']);
        }

        return $this->executerOperation('Transfert', $compteSource, $montant, $compteDestinataire);
    }

    private function operationSimple(string $operation)
    {
        $compte = $this->currentCompte();

        if (! $compte) {
            return redirect()->to('/')->with('error', 'Compte client introuvable.');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->to('/compte')->with('errors', ['Le montant doit etre superieur a 0.']);
        }

        return $this->executerOperation($operation, $compte, $montant);
    }

    private function executerOperation(string $operation, array $compteSource, float $montant, ?array $compteDestinataire = null)
    {
        $db = \Config\Database::connect();
        $compteModel = new CompteClientModel();
        $typeModel = new TypeOperationsModel();
        $transactionModel = new TransactionModel();
        $historiqueModel = new HistoriqueTransactionModel();
        $typeOperation = $typeModel->findByNom($operation);
        $operateur = (new OperateurModel())->findByTelephone($compteSource['telephone']);

        if (! $typeOperation) {
            return redirect()->to('/compte')->with('errors', ['Type d operation introuvable: ' . $operation]);
        }

        if (! $operateur) {
            return redirect()->to('/compte')->with('errors', ['Operateur introuvable pour ce numero.']);
        }

        $bareme = (new FraisModel())->findForAmount((int) $operateur['id'], (int) $typeOperation['id'], $montant);
        $montantFrais = $bareme ? (new FraisModel())->calculerFrais($bareme, $montant) : 0.0;
        $soldeAvantSource = (float) $compteSource['solde'];
        $soldeApresSource = $soldeAvantSource;

        if ($operation === 'Depot') {
            $soldeApresSource += $montant;
        } else {
            $totalDebit = $montant + $montantFrais;

            if ($soldeAvantSource < $totalDebit) {
                return redirect()->to('/compte')->with('errors', ['Solde insuffisant. Montant + frais: ' . number_format($totalDebit, 0, ',', ' ') . ' Ar.']);
            }

            $soldeApresSource -= $totalDebit;
        }

        $db->transStart();

        $transactionId = $transactionModel->insert([
            'id_type_operations' => (int) $typeOperation['id'],
            'montant' => $montant,
            'date' => date('Y-m-d'),
            'id_compte_client' => (int) $compteSource['id'],
            'id_compte_destinataire' => $compteDestinataire['id'] ?? null,
            'montant_frais' => $montantFrais,
        ]);

        $compteModel->update((int) $compteSource['id'], ['solde' => $soldeApresSource]);
        $historiqueModel->insert([
            'id_transaction' => (int) $transactionId,
            'date' => date('Y-m-d'),
            'montant' => $montant,
            'id_type_operations' => (int) $typeOperation['id'],
            'solde_avant' => $soldeAvantSource,
            'solde_apres' => $soldeApresSource,
        ]);

        if ($operation === 'Transfert' && $compteDestinataire !== null) {
            $soldeAvantDestinataire = (float) $compteDestinataire['solde'];
            $soldeApresDestinataire = $soldeAvantDestinataire + $montant;

            $compteModel->update((int) $compteDestinataire['id'], ['solde' => $soldeApresDestinataire]);
            $historiqueModel->insert([
                'id_transaction' => (int) $transactionId,
                'date' => date('Y-m-d'),
                'montant' => $montant,
                'id_type_operations' => (int) $typeOperation['id'],
                'solde_avant' => $soldeAvantDestinataire,
                'solde_apres' => $soldeApresDestinataire,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/compte')->with('errors', ['Erreur pendant l operation.']);
        }

        return redirect()->to('/compte')->with('success', $operation . ' effectue avec succes. Frais: ' . number_format($montantFrais, 0, ',', ' ') . ' Ar.');
    }

    private function currentCompte(): ?array
    {
        $compteId = (int) session()->get('compte_id');

        if ($compteId <= 0) {
            return null;
        }

        return (new CompteClientModel())->withClient()->find($compteId);
    }
}
