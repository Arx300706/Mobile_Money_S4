<?php

namespace App\Controllers;

use App\Models\FraisModel;
use App\Models\OperateurModel;
use App\Models\TypeOperationsModel;

class TypeOperationController extends BaseController
{
    public function index()
    {
        $typeModel = new TypeOperationsModel();
        $fraisModel = new FraisModel();
        $selectedTypeId = (int) ($this->request->getGet('operation_id') ?? 0);

        $fraisQuery = $fraisModel->withDetails();

        if ($selectedTypeId > 0) {
            $fraisQuery->where('frais.id_type_operations', $selectedTypeId);
        }

        return view('TypeOperations', [
            'types' => $typeModel->orderBy('id', 'ASC')->findAll(),
            'operateurs' => (new OperateurModel())->orderBy('nom', 'ASC')->findAll(),
            'frais' => $fraisQuery->findAll(),
            'selectedTypeId' => $selectedTypeId,
            'success' => session()->getFlashdata('success'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $typeModel = new TypeOperationsModel();
        $fraisModel = new FraisModel();
        $db = \Config\Database::connect();
        $nom = trim((string) $this->request->getPost('nom'));

        $db->transStart();

        $typeId = $typeModel->insert(['nom' => $nom]);

        if (! $typeId) {
            $db->transRollback();

            return redirect()->to('/TypeOperation')->with('errors', $typeModel->errors())->withInput();
        }

        $errors = $this->enregistrerBaremes($fraisModel, (int) $typeId);

        if ($errors !== []) {
            $db->transRollback();

            return redirect()->to('/TypeOperation')->with('errors', $errors)->withInput();
        }

        $db->transComplete();

        return redirect()->to('/TypeOperation?operation_id=' . $typeId)
            ->with('success', 'Type d operation cree avec ses tranches.');
    }

    public function update(int $id)
    {
        $typeModel = new TypeOperationsModel();
        $nom = trim((string) $this->request->getPost('nom'));

        if (! $typeModel->find($id)) {
            return redirect()->to('/TypeOperation')->with('errors', ['Type d operation introuvable.']);
        }

        if (! $typeModel->update($id, ['id' => $id, 'nom' => $nom])) {
            return redirect()->to('/TypeOperation?operation_id=' . $id)
                ->with('errors', $typeModel->errors())
                ->withInput();
        }

        return redirect()->to('/TypeOperation?operation_id=' . $id)
            ->with('success', 'Type d operation modifie.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $typeModel = new TypeOperationsModel();

        if (! $typeModel->find($id)) {
            return redirect()->to('/TypeOperation')->with('errors', ['Type d operation introuvable.']);
        }

        $usedInTransactions = $db->table('transaction')
            ->where('id_type_operations', $id)
            ->countAllResults();
        $usedInHistory = $db->table('historique_transaction')
            ->where('id_type_operations', $id)
            ->countAllResults();

        if ($usedInTransactions > 0 || $usedInHistory > 0) {
            return redirect()->to('/TypeOperation?operation_id=' . $id)
                ->with('errors', ['Impossible de supprimer ce type: il est deja utilise dans des transactions.']);
        }

        $db->transStart();
        $db->table('frais')->where('id_type_operations', $id)->delete();
        $typeModel->delete($id);
        $db->transComplete();

        return redirect()->to('/TypeOperation')->with('success', 'Type d operation supprime.');
    }

    private function enregistrerBaremes(FraisModel $fraisModel, int $typeId): array
    {
        $operateurs = (array) $this->request->getPost('id_operateur');
        $mins = (array) $this->request->getPost('tranche_min');
        $maxs = (array) $this->request->getPost('tranche_max');
        $types = (array) $this->request->getPost('type_frais');
        $montants = (array) $this->request->getPost('montant_frais');
        $errors = [];

        foreach ($mins as $index => $min) {
            if ($min === '' && ($maxs[$index] ?? '') === '' && ($montants[$index] ?? '') === '') {
                continue;
            }

            $data = [
                'id_operateur' => (int) ($operateurs[$index] ?? 0),
                'id_type_operations' => $typeId,
                'tranche_min' => (int) $min,
                'tranche_max' => (int) ($maxs[$index] ?? 0),
                'type_frais' => (string) ($types[$index] ?? 'fixe'),
                'montant_frais' => (float) ($montants[$index] ?? 0),
            ];

            if ($data['tranche_max'] < $data['tranche_min']) {
                $errors[] = 'La tranche max doit etre superieure ou egale a la tranche min.';
                continue;
            }

            if ($fraisModel->hasOverlappingTranche(
                $data['id_operateur'],
                $typeId,
                $data['tranche_min'],
                $data['tranche_max']
            )) {
                $errors[] = 'Une tranche chevauche deja ce bareme pour cet operateur.';
                continue;
            }

            if (! $fraisModel->insert($data)) {
                $errors = array_merge($errors, $fraisModel->errors());
            }
        }

        return $errors;
    }
}
