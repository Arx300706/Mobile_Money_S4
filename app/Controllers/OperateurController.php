<?php

namespace App\Controllers;

use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    public function index()
    {
        $operateurModel = new OperateurModel();
        $operateurs = $operateurModel->findAll();

        return view('operateur/index', [
            'operateurs' => $operateurs,
            'success' => session()->getFlashdata('success'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create()
    {
        return view('operateur/form', [
            'title' => 'Ajouter un operateur',
            'operateur' => null,
            'action' => '/operateur/store',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        $model = new OperateurModel();
        $data = $this->operateurData();

        if (! $model->insert($data)) {
            return redirect()->to('/operateur/create')
                ->with('errors', $model->errors())
                ->withInput();
        }

        return redirect()->to('/operateur')->with('success', 'Operateur ajoute.');
    }

    public function edit(int $id)
    {
        $operateur = (new OperateurModel())->find($id);

        if (! $operateur) {
            return redirect()->to('/operateur')->with('errors', ['Operateur introuvable.']);
        }

        return view('operateur/form', [
            'title' => 'Modifier un operateur',
            'operateur' => $operateur,
            'action' => '/operateur/update/' . $id,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update(int $id)
    {
        $model = new OperateurModel();

        if (! $model->find($id)) {
            return redirect()->to('/operateur')->with('errors', ['Operateur introuvable.']);
        }

        if (! $model->update($id, ['id' => $id] + $this->operateurData())) {
            return redirect()->to('/operateur/edit/' . $id)
                ->with('errors', $model->errors())
                ->withInput();
        }

        return redirect()->to('/operateur')->with('success', 'Operateur modifie.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $model = new OperateurModel();

        if (! $model->find($id)) {
            return redirect()->to('/operateur')->with('errors', ['Operateur introuvable.']);
        }

        $usedInFrais = $db->table('frais')->where('id_operateur', $id)->countAllResults();

        if ($usedInFrais > 0) {
            return redirect()->to('/operateur')
                ->with('errors', ['Impossible de supprimer cet operateur: il est utilise dans les baremes de frais.']);
        }

        $model->delete($id);

        return redirect()->to('/operateur')->with('success', 'Operateur supprime.');
    }

    private function operateurData(): array
    {
        return [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prefixe' => (int) $this->request->getPost('prefixe'),
        ];
    }
}
