<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\BesoinModel;
use app\models\VilleModel;
use Flight;

class BesoinController
{
    public function index(): void
    {
        $ville = Flight::request()->query->ville ?? null;
        $villeId = ($ville !== null && $ville !== '') ? (int) $ville : null;

        Flight::render('besoins/index', [
            'villes' => VilleModel::all(),
            'ville_id' => $villeId,
            'besoins' => BesoinModel::list($villeId),
        ]);
    }

    public function store(): void
    {
        $villeId = (int) (Flight::request()->data->ville_id ?? 0);
        $type = trim((string) (Flight::request()->data->type ?? ''));
        $libelle = trim((string) (Flight::request()->data->libelle ?? ''));
        $prixUnitaire = (float) (Flight::request()->data->prix_unitaire ?? 0);
        $quantite = (float) (Flight::request()->data->quantite ?? 0);

        if ($villeId > 0 && in_array($type, ['nature', 'materiaux', 'argent'], true) && $libelle !== '' && $prixUnitaire > 0 && $quantite > 0) {
            BesoinModel::create($villeId, $type, $libelle, $prixUnitaire, $quantite);
        }

        Flight::redirect('/besoins');
    }

    public function delete($id): void
    {
        $besoinId = (int) $id;
        if ($besoinId > 0) {
            BesoinModel::delete($besoinId);
        }
        Flight::redirect('/besoins');
    }
}
