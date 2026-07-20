<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\HistoriqueTransactionModel;

class SituationClientController extends BaseController
{
    public function index()
    {
        $selectedClientId = (int) ($this->request->getGet('client_id') ?? 0);
        $clientModel = new ClientModel();
        $historiqueModel = new HistoriqueTransactionModel();
        $clients = $clientModel->withCompte()
            ->orderBy('client.nom', 'ASC')
            ->orderBy('client.prenom', 'ASC')
            ->findAll();
        $clientsHistoriques = [];

        foreach ($clients as $client) {
            if ($selectedClientId > 0 && (int) $client['id'] !== $selectedClientId) {
                continue;
            }

            $clientsHistoriques[] = [
                'client' => $client,
                'historiques' => $client['compte_id']
                    ? $historiqueModel->findByCompte((int) $client['compte_id'])
                    : [],
            ];
        }

        return view('situation/SituationClient', [
            'clients' => $clients,
            'clientsHistoriques' => $clientsHistoriques,
            'selectedClientId' => $selectedClientId,
            'totalClients' => count($clients),
            'totalComptes' => count(array_filter($clients, static fn (array $client): bool => ! empty($client['compte_id']))),
            'totalSolde' => array_sum(array_map(static fn (array $client): float => (float) ($client['solde'] ?? 0), $clients)),
        ]);
    }
}
