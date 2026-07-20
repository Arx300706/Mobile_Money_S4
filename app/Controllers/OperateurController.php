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
        $prefixes = $this->prefixesFromRequest();
        $errors = $this->validatePrefixes($prefixes);

        if ($errors !== []) {
            return redirect()->to('/operateur')
                ->with('errors', $errors)
                ->withInput();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($prefixes as $prefixe) {
            $existing = $model->findByPrefixe($prefixe);

            if ($existing) {
                $model->update((int) $existing['id'], [
                    'id' => (int) $existing['id'],
                    'nom' => 'OP',
                    'prefixe' => $prefixe,
                ]);
                continue;
            }

            $model->insert([
                'nom' => 'op',
                'prefixe' => $prefixe,
            ]);
        }

        $db->table('operateur')
            ->whereNotIn('prefixe', $prefixes)
            ->update(['nom' => 'Autres Operateurs']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/operateur')
                ->with('errors', ['Erreur pendant l ajout de l operateur.'])
                ->withInput();
        }

        return redirect()->to('/operateur')->with('success', 'Prefixes de notre operateur mis a jour.');
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

        $data = $this->operateurData();
        $prefixes = $this->prefixesFromRequest();
        $errors = $this->validateOperateur($data['nom'], $prefixes, $id);

        if ($errors !== []) {
            return redirect()->to('/operateur/edit/' . $id)
                ->with('errors', $errors)
                ->withInput();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $model->update($id, [
            'id' => $id,
            'nom' => $data['nom'],
            'prefixe' => $prefixes[0],
        ]);

        foreach (array_slice($prefixes, 1) as $prefixe) {
            $model->insert([
                'nom' => $data['nom'],
                'prefixe' => $prefixe,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/operateur/edit/' . $id)
                ->with('errors', ['Erreur pendant la modification de l operateur.'])
                ->withInput();
        }

        return redirect()->to('/operateur')->with('success', count($prefixes) > 1 ? 'Operateur modifie et prefixes ajoutes.' : 'Operateur modifie.');
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
        ];
    }

    private function prefixesFromRequest(): array
    {
        $prefixes = preg_split('/[\s,;]+/', trim((string) $this->request->getPost('prefixe')));
        $prefixes = array_filter($prefixes, static fn (string $prefixe): bool => $prefixe !== '');

        $normalized = array_map(static function (string $prefixe): int {
            if (! ctype_digit($prefixe)) {
                return -1;
            }

            if (strlen($prefixe) === 3 && str_starts_with($prefixe, '0')) {
                $prefixe = substr($prefixe, 1);
            }

            if (strlen($prefixe) > 2) {
                return -1;
            }

            return (int) $prefixe;
        }, $prefixes);

        return array_values(array_unique($normalized));
    }

    private function validateOperateur(string $nom, array $prefixes, ?int $ignoreId = null): array
    {
        $errors = [];
        $model = new OperateurModel();

        if ($nom === '') {
            $errors[] = 'Le nom de l operateur est obligatoire.';
        }

        if (strlen($nom) > 100) {
            $errors[] = 'Le nom de l operateur ne doit pas depasser 100 caracteres.';
        }

        if ($prefixes === []) {
            $errors[] = 'Veuillez entrer au moins un prefixe telephone.';
        }

        foreach ($prefixes as $prefixe) {
            if ($prefixe < 1 || $prefixe > 99) {
                $errors[] = 'Chaque prefixe doit etre compose de 1 ou 2 chiffres.';
                continue;
            }

            $existing = $model->findByPrefixe($prefixe);

            if ($existing && ($ignoreId === null || (int) $existing['id'] !== $ignoreId)) {
                $errors[] = 'Le prefixe ' . $prefixe . ' est deja utilise par ' . $existing['nom'] . '.';
            }
        }

        return array_values(array_unique($errors));
    }

    private function validatePrefixes(array $prefixes): array
    {
        $errors = [];

        if ($prefixes === []) {
            $errors[] = 'Veuillez entrer au moins un prefixe telephone.';
        }

        foreach ($prefixes as $prefixe) {
            if ($prefixe < 1 || $prefixe > 99) {
                $errors[] = 'Chaque prefixe doit etre compose de 1 ou 2 chiffres.';
            }
        }

        return array_values(array_unique($errors));
    }
}
