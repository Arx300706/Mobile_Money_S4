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

    // private function executerOperation(string $operation, array $compteSource, float $montant, ?array $compteDestinataire = null)
    // {
    //     $db = \Config\Database::connect();
    //     $compteModel = new CompteClientModel();
    //     $typeModel = new TypeOperationsModel();
    //     $transactionModel = new TransactionModel();
    //     $historiqueModel = new HistoriqueTransactionModel();
    //     $typeOperation = $typeModel->findByNom($operation);
    //     $operateur = (new OperateurModel())->findByTelephone($compteSource['telephone']);

    //     if (! $typeOperation) {
    //         return redirect()->to('/compte')->with('errors', ['Type d operation introuvable: ' . $operation]);
    //     }

    //     if (! $operateur) {
    //         return redirect()->to('/compte')->with('errors', ['Operateur introuvable pour ce numero.']);
    //     }

    //     $bareme = (new FraisModel())->findForAmount((int) $operateur['id'], (int) $typeOperation['id'], $montant);
    //     $montantFrais = $bareme ? (new FraisModel())->calculerFrais($bareme, $montant) : 0.0;
    //     $soldeAvantSource = (float) $compteSource['solde'];
    //     $soldeApresSource = $soldeAvantSource;

    //     if ($operation === 'Depot') {
    //         $soldeApresSource += $montant;
    //     } else {
    //         $totalDebit = $montant + $montantFrais;

    //         if ($soldeAvantSource < $totalDebit) {
    //             return redirect()->to('/compte')->with('errors', ['Solde insuffisant. Montant + frais: ' . number_format($totalDebit, 0, ',', ' ') . ' Ar.']);
    //         }

    //         $soldeApresSource -= $totalDebit;
    //     }

    //     $db->transStart();

    //     $transactionId = $transactionModel->insert([
    //         'id_type_operations' => (int) $typeOperation['id'],
    //         'montant' => $montant,
    //         'date' => date('Y-m-d'),
    //         'id_compte_client' => (int) $compteSource['id'],
    //         'id_compte_destinataire' => $compteDestinataire['id'] ?? null,
    //         'montant_frais' => $montantFrais,
    //     ]);

    //     $compteModel->update((int) $compteSource['id'], ['solde' => $soldeApresSource]);
    //     $historiqueModel->insert([
    //         'id_transaction' => (int) $transactionId,
    //         'date' => date('Y-m-d'),
    //         'montant' => $montant,
    //         'id_type_operations' => (int) $typeOperation['id'],
    //         'solde_avant' => $soldeAvantSource,
    //         'solde_apres' => $soldeApresSource,
    //     ]);

    //     if ($operation === 'Transfert' && $compteDestinataire !== null) {
    //         $soldeAvantDestinataire = (float) $compteDestinataire['solde'];
    //         $soldeApresDestinataire = $soldeAvantDestinataire + $montant;

    //         $compteModel->update((int) $compteDestinataire['id'], ['solde' => $soldeApresDestinataire]);
    //         $historiqueModel->insert([
    //             'id_transaction' => (int) $transactionId,
    //             'date' => date('Y-m-d'),
    //             'montant' => $montant,
    //             'id_type_operations' => (int) $typeOperation['id'],
    //             'solde_avant' => $soldeAvantDestinataire,
    //             'solde_apres' => $soldeApresDestinataire,
    //         ]);
    //     }

    //     $db->transComplete();

    //     if ($db->transStatus() === false) {
    //         return redirect()->to('/compte')->with('errors', ['Erreur pendant l operation.']);
    //     }

    //     return redirect()->to('/compte')->with('success', $operation . ' effectue avec succes. Frais: ' . number_format($montantFrais, 0, ',', ' ') . ' Ar.');
    // }

    private function executerOperation(string $operation, array $compteSource, float $montant, ?array $compteDestinataire = null)
{
    $db = \Config\Database::connect();
    $compteModel = new CompteClientModel();
    $typeModel = new TypeOperationsModel();
    $transactionModel = new TransactionModel();
    $historiqueModel = new HistoriqueTransactionModel();
    $fraisModel = new FraisModel();

    $typeOperation = $typeModel->findByNom($operation);
    $operateurSource = (new OperateurModel())->findByTelephone($compteSource['telephone']);

    if (! $typeOperation) {
        return redirect()->to('/compte')->with('errors', ['Type d operation introuvable: ' . $operation]);
    }

    if (! $operateurSource) {
        return redirect()->to('/compte')->with('errors', ['Operateur introuvable pour ce numero.']);
    }

    // 1. Calcul des frais initiaux de l'opération courante (Dépôt, Retrait ou Transfert)
    $bareme = $fraisModel->findForAmount((int) $operateurSource['id'], (int) $typeOperation['id'], $montant);
    $montantFrais = $bareme ? $fraisModel->calculerFrais($bareme, $montant) : 0.0;

    // 2. Initialisation des variables spécifiques au Transfert Intra-Opérateur
    $fraisRetraitDestinataire = 0.0;
    $memeOperateur = false;

    if ($operation === 'Transfert' && $compteDestinataire !== null) {
        $operateurDestinataire = (new OperateurModel())->findByTelephone($compteDestinataire['telephone']);
        
        // Vérification si les deux opérateurs portent le même nom
        if ($operateurDestinataire && $operateurSource['nom'] === $operateurDestinataire['nom']) {
            $memeOperateur = true;
            
            // Récupération du type d'opération "Retrait" pour calculer ses frais correspondants
            $typeRetrait = $typeModel->findByNom('Retrait');
            if ($typeRetrait) {
                $baremeRetrait = $fraisModel->findForAmount((int) $operateurDestinataire['id'], (int) $typeRetrait['id'], $montant);
                $fraisRetraitDestinataire = $baremeRetrait ? $fraisModel->calculerFrais($baremeRetrait, $montant) : 0.0;
            }
        }
    }

    $soldeAvantSource = (float) $compteSource['solde'];
    $soldeApresSource = $soldeAvantSource;

    // 3. Gestion des débits / crédits de la source
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

    // Enregistrement de la transaction avec les frais payés par la source
    $transactionId = $transactionModel->insert([
        'id_type_operations' => (int) $typeOperation['id'],
        'montant' => $montant,
        'date' => date('Y-m-d'),
        'id_compte_client' => (int) $compteSource['id'],
        'id_compte_destinataire' => $compteDestinataire['id'] ?? null,
        'montant_frais' => $montantFrais, 
    ]);

    // Mise à jour et historique du compte source
    $compteModel->update((int) $compteSource['id'], ['solde' => $soldeApresSource]);
    $historiqueModel->insert([
        'id_transaction' => (int) $transactionId,
        'date' => date('Y-m-d'),
        'montant' => $montant,
        'id_type_operations' => (int) $typeOperation['id'],
        'solde_avant' => $soldeAvantSource,
        'solde_apres' => $soldeApresSource,
    ]);

    // 4. Traitement du destinataire en cas de Transfert
    if ($operation === 'Transfert' && $compteDestinataire !== null) {
        $soldeAvantDestinataire = (float) $compteDestinataire['solde'];
        
        // Application de la déduction : si même opérateur, le destinataire reçoit (montant - frais de retrait)
        $montantRecu = $montant - $fraisRetraitDestinataire;
        $soldeApresDestinataire = $soldeAvantDestinataire + $montantRecu;

        $compteModel->update((int) $compteDestinataire['id'], ['solde' => $soldeApresDestinataire]);
        $historiqueModel->insert([
            'id_transaction' => (int) $transactionId,
            'date' => date('Y-m-d'),
            // Le montant inscrit dans l'historique du destinataire reflète la somme nette ajoutée
            'montant' => $montantRecu,
            'id_type_operations' => (int) $typeOperation['id'],
            'solde_avant' => $soldeAvantDestinataire,
            'solde_apres' => $soldeApresDestinataire,
        ]);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->to('/compte')->with('errors', ['Erreur pendant l operation.']);
    }

    // Message de succès dynamique informant des frais appliqués
    $messageSucces = $operation . ' effectue avec succes.';
    if ($memeOperateur) {
        $messageSucces .= ' Même opérateur détecté : Frais de transfert source de ' . number_format($montantFrais, 0, ',', ' ') . ' Ar et frais de retrait déduits au destinataire de ' . number_format($fraisRetraitDestinataire, 0, ',', ' ') . ' Ar.';
    } else {
        $messageSucces .= ' Frais: ' . number_format($montantFrais, 0, ',', ' ') . ' Ar.';
    }

    return redirect()->to('/compte')->with('success', $messageSucces);
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
