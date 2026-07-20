<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class OperateurController extends BaseController
{
    public function index()
    {
        $operateurModel = new \App\Models\OperateurModel();
        $operateurs = $operateurModel->findAll();

        return view('operateur/index', ['operateurs' => $operateurs]);
    }

    public function save()
    {
        $operateurModel = new \App\Models\OperateurModel();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prefixe' => $this->request->getPost('prefixe'),
        ];

        if ($operateurModel->insert($data)) {
            return redirect()->to('/operateur')->with('success', 'Opérateur ajouté avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout de l\'opérateur.');
        }
    }

    public function edit($id)
    {
        $operateurModel = new \App\Models\OperateurModel();
        $operateur = $operateurModel->find($id);

        if (!$operateur) {
            return redirect()->to('/operateur')->with('error', 'Opérateur non trouvé.');
        }

        return view('operateur/edit', ['operateur' => $operateur]);
    }

    public function delete($id){
        $operateurModel = new \App\Models\OperateurModel();
        if ($operateurModel->delete($id)) {
            return redirect()->to('/operateur')->with('success', 'Opérateur supprimé avec succès.');
        } else {
            return redirect()->to('/operateur')->with('error', 'Erreur lors de la suppression de l\'opérateur.');
        }
    }
}
