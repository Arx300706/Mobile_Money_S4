<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;
use App\Models\ClientModel;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin');
        }

        if (session()->get('role') === 'client' && session()->get('client_id')) {
            return redirect()->to('/accueil');
        }

        return view('login', [
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ]);
    }

    public function clientConnexion()
    {
        $telephone = trim((string) $this->request->getPost('telephone'));

        if (!$telephone) {
            return redirect()->to('/')->with('error', 'Veuillez entrer votre numero de telephone.');
        }

        if (strtolower($telephone) === 'admin') {
            session()->set('admin_login_pending', true);

            return redirect()->to('/admin/password');
        }

        $compte = (new CompteClientModel())->findByTelephone($telephone);

        if (! $compte) {
            return redirect()->to('/')->with('error', 'Numero introuvable. Aucun compte client ne correspond a ce telephone.');
        }

        session()->set([
            'role' => 'client',
            'client_id' => (int) $compte['id_client'],
            'compte_id' => (int) $compte['id'],
            'client_nom' => trim($compte['nom'] . ' ' . $compte['prenom']),
            'client_telephone' => $compte['telephone'],
        ]);

        return redirect()->to('/compte');
    }

    public function adminPassword()
    {
        if (!session()->get('admin_login_pending')) {
            return redirect()->to('/');
        }

        return view('admin_password', [
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function adminPasswordCheck()
    {
        if (!session()->get('admin_login_pending')) {
            return redirect()->to('/');
        }

        $password = (string) $this->request->getPost('password');

        if ($password !== 'admin123') {
            return redirect()->to('/admin/password')->with('error', 'Mot de passe admin incorrect.');
        }

        session()->remove('admin_login_pending');
        session()->set([
            'role' => 'admin',
            'admin_nom' => 'Administrateur',
        ]);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/')->with('success', 'Vous etes deconnecte.');
    }
}
