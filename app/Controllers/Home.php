<?php

namespace App\Controllers;

use App\Models\CaisseModel;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('client_id')) {
            return redirect()->to('/');
        }

        $model = new CaisseModel();

        $data = [
            'caisses' => $model->orderBy('id', 'ASC')->findAll(),
            'error' => session()->getFlashdata('error'),
        ];

        return view('accueil', $data);
    }

    public function caisseSelect()
    {
        if (!session()->get('client_id')) {
            return redirect()->to('/');
        }

        $caisseId = $this->request->getPost('caisse_id');

        if (!$caisseId) {
            return redirect()->to('/accueil')->with('error', 'Veuillez choisir une caisse.');
        }

        $model = new CaisseModel();
        $caisse = $model->find($caisseId);

        if (!$caisse) {
            return redirect()->to('/accueil')->with('error', 'La caisse choisie est introuvable.');
        }

        session()->set('caisse_id', (int) $caisseId);

        return redirect()->to('/achat');
    }
}
