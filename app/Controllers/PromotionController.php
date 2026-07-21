<?php

namespace App\Controllers;

use App\Models\PromotionFraisModel;
use App\Models\TypeOperationsModel;

class PromotionController extends BaseController
{
    public function index()
    {
        $promotion = $this->promotionMemeOperateur();

        return view('promotion/index', [
            'promotion' => $promotion,
            'success' => session()->getFlashdata('success'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update()
    {
        $promotionModel = new PromotionFraisModel();
        $promotion = $this->promotionMemeOperateur();

        if (! $promotion) {
            return redirect()->to('/promotion')->with('errors', ['Configuration promotion introuvable.']);
        }

        $data = [
            'nom' => $promotion['nom'],
            'id_type_operations' => (int) $promotion['id_type_operations'],
            'cible' => 'meme_operateur',
            'type_promotion' => $this->validTypePromotion((string) $this->request->getPost('type_promotion')),
            'valeur' => (float) $this->request->getPost('valeur'),
            'actif' => $this->request->getPost('actif') ? 1 : 0,
        ];

        if (! $promotionModel->update((int) $promotion['id'], $data)) {
            return redirect()->to('/promotion')
                ->with('errors', $promotionModel->errors())
                ->withInput();
        }

        return redirect()->to('/promotion')->with('success', 'Promotion modifiee.');
    }

    private function promotionMemeOperateur(): ?array
    {
        $typeTransfert = (new TypeOperationsModel())->findByNom('Transfert');

        if (! $typeTransfert) {
            return null;
        }

        $promotionModel = new PromotionFraisModel();
        $promotion = $promotionModel
            ->where('id_type_operations', (int) $typeTransfert['id'])
            ->where('cible', 'meme_operateur')
            ->first();

        if ($promotion) {
            return $promotion;
        }

        $id = $promotionModel->insert([
            'nom' => 'Promotion frais transfert meme operateur',
            'id_type_operations' => (int) $typeTransfert['id'],
            'cible' => 'meme_operateur',
            'type_promotion' => 'pourcentage',
            'valeur' => 0,
            'actif' => 0,
        ]);

        return $id ? $promotionModel->find((int) $id) : null;
    }

    private function validTypePromotion(string $typePromotion): string
    {
        return in_array($typePromotion, ['fixe', 'pourcentage'], true) ? $typePromotion : 'pourcentage';
    }
}
