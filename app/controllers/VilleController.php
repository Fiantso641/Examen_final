<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\VilleModel;
use Flight;

class VilleController
{
    public function index(): void
    {
        Flight::render('villes/index', [
            'villes' => VilleModel::all(),
        ]);
    }

    public function store(): void
    {
        $nom = trim((string) (Flight::request()->data->nom ?? ''));
        $region = trim((string) (Flight::request()->data->region ?? ''));
        $region = ($region === '') ? null : $region;

        if ($nom !== '') {
            VilleModel::create($nom, $region);
        }

        Flight::redirect('/villes');
    }

    public function update($id): void
    {
        $villeId = (int) $id;
        $nom = trim((string) (Flight::request()->data->nom ?? ''));
        $region = trim((string) (Flight::request()->data->region ?? ''));
        $region = ($region === '') ? null : $region;

        if ($villeId > 0 && $nom !== '') {
            VilleModel::update($villeId, $nom, $region);
        }

        Flight::redirect('/villes');
    }

    public function delete($id): void
    {
        $villeId = (int) $id;
        if ($villeId > 0) {
            VilleModel::delete($villeId);
        }
        Flight::redirect('/villes');
    }
}
