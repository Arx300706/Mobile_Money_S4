<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
        $client = trim((string) $this->request->getPost('client'));

        if (!$client) {
            return redirect()->to('/')->with('error', 'Veuillez entrer le nom du client.');
        }

        if (strtolower($client) === 'admin') {
            session()->set('admin_login_pending', true);

            return redirect()->to('/admin/password');
        }

        $clientModel = new ClientModel();
        $clientId = $clientModel->insert([
            'nom' => $client,
            'date' => date('Y-m-d H:i:s'),
        ]);

        session()->set([
            'role' => 'client',
            'client_id' => (int) $clientId,
            'client_nom' => $client,
        ]);

        return redirect()->to('/accueil');
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
