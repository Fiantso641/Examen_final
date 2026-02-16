<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\DispatchModel;
use Flight;

class DashboardController
{
    public function index(): void
    {
        $rows = DispatchModel::dashboardRows();

        $villes = [];
        foreach ($rows as $r) {
            $vid = $r['ville_id'];
            if (!isset($villes[$vid])) {
                $villes[$vid] = [
                    'ville_id' => (int) $r['ville_id'],
                    'ville_nom' => (string) $r['ville_nom'],
                    'region' => $r['region'],
                    'besoins' => [],
                ];
            }

            if ($r['besoin_id'] !== null) {
                $q = (float) $r['quantite'];
                $qa = (float) $r['quantite_attribuee'];
                $villes[$vid]['besoins'][] = [
                    'besoin_id' => (int) $r['besoin_id'],
                    'type' => (string) $r['type'],
                    'libelle' => (string) $r['libelle'],
                    'prix_unitaire' => (float) $r['prix_unitaire'],
                    'quantite' => $q,
                    'quantite_attribuee' => $qa,
                    'quantite_restante' => max(0, $q - $qa),
                ];
            }
        }

        Flight::render('dashboard/index', [
            'villes' => array_values($villes),
        ]);
    }
}
