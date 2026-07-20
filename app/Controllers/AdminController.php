<?php

namespace App\Controllers;

use App\Models\AchatModel;
use App\Models\CaisseModel;
use App\Models\ClientModel;
use App\Models\ProduitModel;

class AdminController extends BaseController
{
    public function index()
    {
        $produitModel = new ProduitModel();
        $clientModel = new ClientModel();
        $caisseModel = new CaisseModel();
        $achatModel = new AchatModel();

        return view('admin/dashboard', [
            'produitsCount' => $produitModel->countAllResults(),
            'clientsCount' => $clientModel->countAllResults(),
            'caissesCount' => $caisseModel->countAllResults(),
            'achatsCount' => $achatModel->countAllResults(),
            'caisses' => $caisseModel->orderBy('id', 'ASC')->findAll(),
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function produits()
    {
        $model = new ProduitModel();

        return view('admin/produits/index', [
            'produits' => $model->orderBy('designation', 'ASC')->findAll(),
            'success' => session()->getFlashdata('success'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function createProduit()
    {
        return view('admin/produits/form', [
            'produit' => null,
            'action' => '/admin/produits/store',
            'title' => 'Ajouter un produit',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function storeProduit()
    {
        $data = $this->produitData();
        $errors = $this->validateProduit($data);

        if ($errors !== []) {
            return redirect()->to('/admin/produits/create')->with('errors', $errors)->withInput();
        }

        (new ProduitModel())->insert($data);

        return redirect()->to('/admin/produits')->with('success', 'Produit ajoute avec succes.');
    }

    public function editProduit(int $id)
    {
        $produit = (new ProduitModel())->find($id);

        if (!$produit) {
            return redirect()->to('/admin/produits')->with('errors', ['Produit introuvable.']);
        }

        return view('admin/produits/form', [
            'produit' => $produit,
            'action' => '/admin/produits/update/' . $id,
            'title' => 'Modifier le produit',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function updateProduit(int $id)
    {
        $model = new ProduitModel();

        if (!$model->find($id)) {
            return redirect()->to('/admin/produits')->with('errors', ['Produit introuvable.']);
        }

        $data = $this->produitData();
        $errors = $this->validateProduit($data);

        if ($errors !== []) {
            return redirect()->to('/admin/produits/edit/' . $id)->with('errors', $errors)->withInput();
        }

        $model->update($id, $data);

        return redirect()->to('/admin/produits')->with('success', 'Produit modifie avec succes.');
    }

    public function deleteProduit(int $id)
    {
        (new ProduitModel())->delete($id);

        return redirect()->to('/admin/produits')->with('success', 'Produit supprime.');
    }

    public function clients()
    {
        $model = new ClientModel();

        return view('admin/clients', [
            'clients' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function caisses()
    {
        $model = new CaisseModel();

        return view('admin/caisses', [
            'caisses' => $model->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function caisseDetails(int $id)
    {
        $caisseModel = new CaisseModel();
        $caisse = $caisseModel->find($id);

        if (!$caisse) {
            return redirect()->to('/admin/caisses')->with('error', 'Caisse introuvable.');
        }

        return view('admin/caisse_details', [
            'caisse' => $caisse,
            'achats' => $this->achatsQuery()->where('achat.caisse_id', $id)->get()->getResultArray(),
        ]);
    }

    public function achats()
    {
        return view('admin/achats', [
            'achats' => $this->achatsQuery()->get()->getResultArray(),
        ]);
    }

    private function produitData(): array
    {
        return [
            'designation' => trim((string) $this->request->getPost('designation')),
            'prix' => (float) $this->request->getPost('prix'),
            'stock' => (int) $this->request->getPost('stock'),
        ];
    }

    private function validateProduit(array $data): array
    {
        $errors = [];

        if ($data['designation'] === '') {
            $errors[] = 'La designation est obligatoire.';
        }

        if ($data['prix'] <= 0) {
            $errors[] = 'Le prix doit etre superieur a 0.';
        }

        if ($data['stock'] < 0) {
            $errors[] = 'Le stock ne peut pas etre negatif.';
        }

        return $errors;
    }

    private function achatsQuery()
    {
        return \Config\Database::connect()
            ->table('achat')
            ->select('achat.*, produit.designation, client.nom AS client_nom, caisse.date AS caisse_date')
            ->join('produit', 'produit.id = achat.produit_id', 'left')
            ->join('client', 'client.id = achat.client_id', 'left')
            ->join('caisse', 'caisse.id = achat.caisse_id', 'left')
            ->orderBy('achat.id', 'DESC');
    }
}
