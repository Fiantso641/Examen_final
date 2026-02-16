<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\BesoinModel;
use app\models\DispatchModel;
use app\models\DonModel;
use Flight;

class DispatchController
{
    public function simuler(): void
    {
        $db = Flight::db();

        $dons = DonModel::listOpenForDispatch();
        $besoins = BesoinModel::listOpenForDispatch();

        $db->beginTransaction();
        try {
            DispatchModel::resetAllocations();

            foreach ($dons as $don) {
                $donId = (int) $don['id'];
                $donType = (string) $don['type'];
                $donLibelle = (string) $don['libelle'];
                $donRest = (float) $don['quantite_restante'];

                if ($donRest <= 0) {
                    continue;
                }

                foreach ($besoins as &$besoin) {
                    $besoinRest = (float) $besoin['quantite_restante'];
                    if ($besoinRest <= 0) {
                        continue;
                    }

                    if ((string) $besoin['type'] !== $donType) {
                        continue;
                    }

                    if ($donType !== 'argent' && (string) $besoin['libelle'] !== $donLibelle) {
                        continue;
                    }

                    $qty = min($donRest, $besoinRest);
                    if ($qty <= 0) {
                        continue;
                    }

                    DispatchModel::allocate($donId, (int) $besoin['id'], $qty);

                    $donRest -= $qty;
                    $besoin['quantite_restante'] = $besoinRest - $qty;

                    if ($donRest <= 0) {
                        break;
                    }
                }
                unset($besoin);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        Flight::redirect('/dashboard');
    }
}
