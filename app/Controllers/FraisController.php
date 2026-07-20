<?php

namespace App\Controllers;

use App\Models\FraisModel;

class FraisController extends BaseController
{
    public function store()
    {
        $fraisModel = new FraisModel();
        $data = $this->fraisData();
        $errors = $this->validateBareme($fraisModel, $data);

        if ($errors !== []) {
            return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
                ->with('errors', $errors)
                ->withInput();
        }

        if (! $fraisModel->insert($data)) {
            return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
                ->with('errors', $fraisModel->errors())
                ->withInput();
        }

        return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
            ->with('success', 'Bareme de frais ajoute.');
    }

    public function update(int $id)
    {
        $fraisModel = new FraisModel();
        $data = $this->fraisData();

        if (! $fraisModel->find($id)) {
            return redirect()->to('/TypeOperation')->with('errors', ['Bareme introuvable.']);
        }

        $errors = $this->validateBareme($fraisModel, $data, $id);

        if ($errors !== []) {
            return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
                ->with('errors', $errors)
                ->withInput();
        }

        if (! $fraisModel->update($id, $data)) {
            return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
                ->with('errors', $fraisModel->errors())
                ->withInput();
        }

        return redirect()->to('/TypeOperation?operation_id=' . $data['id_type_operations'])
            ->with('success', 'Bareme de frais modifie.');
    }

    public function delete(int $id)
    {
        $fraisModel = new FraisModel();
        $frais = $fraisModel->find($id);

        if (! $frais) {
            return redirect()->to('/TypeOperation')->with('errors', ['Bareme introuvable.']);
        }

        $fraisModel->delete($id);

        return redirect()->to('/TypeOperation?operation_id=' . $frais['id_type_operations'])
            ->with('success', 'Bareme de frais supprime.');
    }

    private function fraisData(): array
    {
        return [
            'id_operateur' => (int) $this->request->getPost('id_operateur'),
            'id_type_operations' => (int) $this->request->getPost('id_type_operations'),
            'tranche_min' => (int) $this->request->getPost('tranche_min'),
            'tranche_max' => (int) $this->request->getPost('tranche_max'),
            'type_frais' => 'fixe',
            'montant_frais' => (float) $this->request->getPost('montant_frais'),
        ];
    }

    private function validateBareme(FraisModel $fraisModel, array $data, ?int $ignoreId = null): array
    {
        $errors = [];

        if ($data['tranche_max'] < $data['tranche_min']) {
            $errors[] = 'La tranche max doit etre superieure ou egale a la tranche min.';
        }

        if ($fraisModel->hasOverlappingTranche(
            $data['id_operateur'],
            $data['id_type_operations'],
            $data['tranche_min'],
            $data['tranche_max'],
            $ignoreId
        )) {
            $errors[] = 'Cette tranche chevauche deja un autre bareme pour cet operateur et ce type.';
        }

        return $errors;
    }
}
