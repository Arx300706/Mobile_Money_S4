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
}
