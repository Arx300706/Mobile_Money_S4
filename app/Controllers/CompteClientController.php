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

        $montantTotal = (float) $this->request->getPost('montant');
        $telephones = $this->telephonesDestinataires();

        if ($montantTotal <= 0) {
            return redirect()->to('/compte')->with('errors', ['Le montant du transfert doit etre superieur a 0.'])->withInput();
        }

        if ($telephones === []) {
            return redirect()->to('/compte')->with('errors', ['Veuillez entrer au moins un numero destinataire.'])->withInput();
        }

        return $this->executerTransferts($compteSource, $montantTotal, $telephones);
    }

    private function operationSimple(string $operation)
    {
        $compte = $this->currentCompte();

        if (! $compte) {
            return redirect()->to('/')->with('error', 'Compte client introuvable.');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->to('/compte')->with('errors', ['Le montant doit etre superieur a 0.'])->withInput();
        }

        return $this->executerOperationSimple($operation, $compte, $montant);
    }

    private function executerOperationSimple(string $operation, array $compte, float $montant)
    {
        $typeOperation = (new TypeOperationsModel())->findByNom($operation);
        $operateur = (new OperateurModel())->findByTelephone($compte['telephone']);

        if (! $typeOperation) {
            return redirect()->to('/compte')->with('errors', ['Type d operation introuvable: ' . $operation]);
        }

        if (! $operateur) {
            return redirect()->to('/compte')->with('errors', ['Operateur introuvable pour ce numero.']);
        }

        if (($operateur['nom'] ?? '') !== 'OP') {
            return redirect()->to('/compte')->with('errors', [$operation . ' disponible seulement pour les clients OP.']);
        }

        $fraisModel = new FraisModel();
        $bareme = $fraisModel->findForAmount((int) $operateur['id'], (int) $typeOperation['id'], $montant);
        $montantFrais = $bareme ? $fraisModel->calculerFrais($bareme, $montant) : 0.0;
        $soldeAvant = (float) $compte['solde'];
        $soldeApres = $operation === 'Depot' ? $soldeAvant + $montant : $soldeAvant - ($montant + $montantFrais);

        if ($soldeApres < 0) {
            return redirect()->to('/compte')->with('errors', ['Solde insuffisant. Montant + frais: ' . number_format($montant + $montantFrais, 0, ',', ' ') . ' Ar.']);
        }

        $db = \Config\Database::connect();
        $transactionModel = new TransactionModel();
        $compteModel = new CompteClientModel();
        $historiqueModel = new HistoriqueTransactionModel();

        $db->transStart();

        $transactionId = $transactionModel->insert([
            'id_type_operations' => (int) $typeOperation['id'],
            'montant' => $montant,
            'date' => date('Y-m-d'),
            'id_compte_client' => (int) $compte['id'],
            'id_compte_destinataire' => null,
            'montant_frais' => $montantFrais,
        ]);

        $compteModel->update((int) $compte['id'], ['solde' => $soldeApres]);
        $historiqueModel->insert([
            'id_transaction' => (int) $transactionId,
            'date' => date('Y-m-d'),
            'montant' => $montant,
            'id_type_operations' => (int) $typeOperation['id'],
            'solde_avant' => $soldeAvant,
            'solde_apres' => $soldeApres,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/compte')->with('errors', ['Erreur pendant l operation.']);
        }

        return redirect()->to('/compte')->with('success', $operation . ' effectue avec succes. Frais: ' . number_format($montantFrais, 0, ',', ' ') . ' Ar.');
    }

    private function executerTransferts(array $compteSource, float $montantTotal, array $telephones)
    {
        $compteModel = new CompteClientModel();
        $operateurModel = new OperateurModel();
        $typeOperation = (new TypeOperationsModel())->findByNom('Transfert');
        $operateurSource = $operateurModel->findByTelephone($compteSource['telephone']);
        $comptesDestinataires = [];
        $errors = [];

        if (! $typeOperation) {
            return redirect()->to('/compte')->with('errors', ['Type d operation introuvable: Transfert']);
        }

        if (! $operateurSource) {
            return redirect()->to('/compte')->with('errors', ['Operateur introuvable pour ce numero.']);
        }

        foreach ($telephones as $telephone) {
            $compteDestinataire = $compteModel->findByTelephone($telephone);
            $operateurDestinataire = $operateurModel->findByTelephone($telephone);

            if (! $compteDestinataire) {
                $errors[] = 'Compte destinataire introuvable: ' . $telephone;
                continue;
            }

            if ((int) $compteDestinataire['id'] === (int) $compteSource['id']) {
                $errors[] = 'Impossible de transferer vers votre propre compte: ' . $telephone;
                continue;
            }

            if (! $operateurDestinataire || ($operateurDestinataire['nom'] ?? '') !== ($operateurSource['nom'] ?? '')) {
                $errors[] = 'Le numero ' . $telephone . ' n est pas du meme operateur que votre compte.';
                continue;
            }

            $comptesDestinataires[] = [
                'compte' => $compteDestinataire,
                'operateur' => $operateurDestinataire,
            ];
        }

        if ($errors !== []) {
            return redirect()->to('/compte')->with('errors', $errors)->withInput();
        }

        $montants = $this->montantsPartages($montantTotal, count($comptesDestinataires));
        $fraisModel = new FraisModel();
        $totalFrais = 0.0;
        $transferts = [];

        foreach ($comptesDestinataires as $index => $destinataire) {
            $montant = $montants[$index];
            $bareme = $fraisModel->findForAmount((int) $operateurSource['id'], (int) $typeOperation['id'], $montant);
            $frais = $bareme ? $fraisModel->calculerFrais($bareme, $montant) : 0.0;
            $totalFrais += $frais;
            $transferts[] = [
                'compte' => $destinataire['compte'],
                'montant' => $montant,
                'frais' => $frais,
            ];
        }

        $totalDebit = $montantTotal + $totalFrais;

        if ((float) $compteSource['solde'] < $totalDebit) {
            return redirect()->to('/compte')->with('errors', ['Solde insuffisant. Montant + frais: ' . number_format($totalDebit, 0, ',', ' ') . ' Ar.'])->withInput();
        }

        $db = \Config\Database::connect();
        $transactionModel = new TransactionModel();
        $historiqueModel = new HistoriqueTransactionModel();
        $soldeSource = (float) $compteSource['solde'];

        $db->transStart();

        foreach ($transferts as $transfert) {
            $soldeAvantSource = $soldeSource;
            $soldeSource -= $transfert['montant'] + $transfert['frais'];

            $transactionId = $transactionModel->insert([
                'id_type_operations' => (int) $typeOperation['id'],
                'montant' => $transfert['montant'],
                'date' => date('Y-m-d'),
                'id_compte_client' => (int) $compteSource['id'],
                'id_compte_destinataire' => (int) $transfert['compte']['id'],
                'montant_frais' => $transfert['frais'],
            ]);

            $historiqueModel->insert([
                'id_transaction' => (int) $transactionId,
                'date' => date('Y-m-d'),
                'montant' => $transfert['montant'],
                'id_type_operations' => (int) $typeOperation['id'],
                'solde_avant' => $soldeAvantSource,
                'solde_apres' => $soldeSource,
            ]);

            $soldeAvantDestinataire = (float) $transfert['compte']['solde'];
            $soldeApresDestinataire = $soldeAvantDestinataire + $transfert['montant'];

            $compteModel->update((int) $transfert['compte']['id'], ['solde' => $soldeApresDestinataire]);
            $historiqueModel->insert([
                'id_transaction' => (int) $transactionId,
                'date' => date('Y-m-d'),
                'montant' => $transfert['montant'],
                'id_type_operations' => (int) $typeOperation['id'],
                'solde_avant' => $soldeAvantDestinataire,
                'solde_apres' => $soldeApresDestinataire,
            ]);
        }

        $compteModel->update((int) $compteSource['id'], ['solde' => $soldeSource]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/compte')->with('errors', ['Erreur pendant les transferts.']);
        }

        return redirect()->to('/compte')->with('success', count($transferts) . ' transfert(s) effectue(s). Montant partage: ' . number_format($montantTotal, 0, ',', ' ') . ' Ar. Frais: ' . number_format($totalFrais, 0, ',', ' ') . ' Ar.');
    }

    private function telephonesDestinataires(): array
    {
        $telephoneInput = (string) $this->request->getPost('telephone_destinataire');
        $telephones = preg_split('/[\s,;]+/', trim($telephoneInput));
        $telephones = array_filter($telephones, static fn (string $telephone): bool => $telephone !== '');

        return array_values(array_unique($telephones));
    }

    private function montantsPartages(float $montantTotal, int $nombreDestinataires): array
    {
        $montantBase = floor(($montantTotal / $nombreDestinataires) * 100) / 100;
        $montants = array_fill(0, $nombreDestinataires, $montantBase);
        $reste = round($montantTotal - ($montantBase * $nombreDestinataires), 2);
        $montants[$nombreDestinataires - 1] = round($montants[$nombreDestinataires - 1] + $reste, 2);

        return $montants;
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
