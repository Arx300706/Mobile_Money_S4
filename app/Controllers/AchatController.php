<?php

namespace App\Controllers;

use App\Models\AchatModel;
use App\Models\CaisseModel;
use App\Models\ProduitModel;

class AchatController extends BaseController
{
    public function index()
    {
        $caisseId = session()->get('caisse_id');
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if (!$caisseId) {
            return redirect()->to('/accueil');
        }

        $caisseModel = new CaisseModel();
        $produitModel = new ProduitModel();
        $achatModel = new AchatModel();

        $caisse = $caisseModel->find($caisseId);

        if (!$caisse) {
            session()->remove('caisse_id');

            return redirect()->to('/accueil');
        }

        $achats = $achatModel->findByCaisseAndClient((int) $caisseId, (int) $clientId);
        $total = 0;

        foreach ($achats as $achat) {
            $total += $achat['quantite'] * $achat['prix_unitaire'];
        }

        return view('achat_form', [
            'caisse' => $caisse,
            'produits' => $produitModel->orderBy('designation', 'ASC')->findAll(),
            'achats' => $achats,
            'total' => $total,
            'clientNom' => session()->get('client_nom'),
            'errors' => session()->getFlashdata('errors') ?? [],
            'success' => session()->getFlashdata('success'),
        ]);
    }

    public function store()
    {
        $caisseId = session()->get('caisse_id');
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if (!$caisseId) {
            return redirect()->to('/accueil');
        }

        $produitId = (int) $this->request->getPost('produit_id');
        $quantite = (int) $this->request->getPost('quantite');

        $produitModel = new ProduitModel();
        $caisseModel = new CaisseModel();
        $achatModel = new AchatModel();
        $produit = $produitModel->find($produitId);
        $caisse = $caisseModel->find($caisseId);

        $errors = [];

        if (!$caisse) {
            session()->remove('caisse_id');

            return redirect()->to('/')->with('error', 'La caisse choisie est introuvable.');
        }

        if (!$produit) {
            $errors[] = 'Veuillez choisir un produit valide.';
        }

        if ($quantite <= 0) {
            $errors[] = 'La quantite doit etre superieure a 0.';
        }

        if ($produit && $quantite > (int) $produit['stock']) {
            $errors[] = 'La quantite demandee depasse le stock disponible.';
        }

        if ($errors !== []) {
            return redirect()->to('/achat')->with('errors', $errors)->withInput();
        }

        $montantAchat = $quantite * (float) $produit['prix'];
        $db = \Config\Database::connect();

        $db->transStart();

        $achatModel->insert([
            'produit_id' => $produitId,
            'caisse_id' => $caisseId,
            'client_id' => $clientId,
            'quantite' => $quantite,
            'prix_unitaire' => $produit['prix'],
        ]);

        $db->table('produit')
            ->where('id', $produitId)
            ->set('stock', 'stock - ' . $quantite, false)
            ->update();

        $db->table('caisse')
            ->where('id', $caisseId)
            ->set('montant_total', 'montant_total + ' . $montantAchat, false)
            ->set('date', date('Y-m-d H:i:s'))
            ->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/achat')
                ->with('errors', ['Une erreur est survenue pendant l\'enregistrement de l\'achat.'])
                ->withInput();
        }

        return redirect()->to('/achat')->with('success', 'Achat ajoute avec succes.');
    }

    public function cloturer()
    {
        session()->remove(['client_id', 'client_nom', 'caisse_id']);

        return redirect()->to('/')->with('success', 'Achat cloture. Vous pouvez saisir le prochain client.');
    }
}
