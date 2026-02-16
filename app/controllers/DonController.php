<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\DonModel;
use Flight;

class DonController
{
    public function index(): void
    {
        Flight::render('dons/index', [
            'dons' => DonModel::all(),
        ]);
    }

    public function store(): void
    {
        $type = trim((string) (Flight::request()->data->type ?? ''));
        $libelle = trim((string) (Flight::request()->data->libelle ?? ''));
        $prixUnitaire = (float) (Flight::request()->data->prix_unitaire ?? 0);
        $quantite = (float) (Flight::request()->data->quantite ?? 0);
        $dateDon = trim((string) (Flight::request()->data->date_don ?? ''));

        if ($dateDon === '') {
            $dateDon = date('Y-m-d');
        }

        if (in_array($type, ['nature', 'materiaux', 'argent'], true) && $libelle !== '' && $prixUnitaire > 0 && $quantite > 0) {
            DonModel::create($type, $libelle, $prixUnitaire, $quantite, $dateDon);
        }

        Flight::redirect('/dons');
    }

    public function delete($id): void
    {
        $donId = (int) $id;
        if ($donId > 0) {
            DonModel::delete($donId);
        }
        Flight::redirect('/dons');
    }
}
