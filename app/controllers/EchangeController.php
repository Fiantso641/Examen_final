<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\EchangeModel;
use app\models\ObjetModel;
use app\utils\Auth;
use Flight;

class EchangeController
{
    public function index(): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        Flight::render('echanges/index', [
            'echanges' => EchangeModel::listForUser($userId),
            'user_id' => $userId,
        ]);
    }

    public function proposer(): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();

        $objetDemandeId = (int) (Flight::request()->data->objet_demande_id ?? 0);
        $objetProposeId = (int) (Flight::request()->data->objet_propose_id ?? 0);

        $objetDemande = ObjetModel::findById($objetDemandeId);
        if (!$objetDemande) {
            Flight::halt(400, 'Objet demande introuvable');
            return;
        }
        if ((int) $objetDemande['user_id'] === $userId) {
            Flight::halt(400, 'Tu ne peux pas proposer un echange sur ton propre objet');
            return;
        }

        $mesObjets = ObjetModel::listMine($userId);
        $ok = false;
        foreach ($mesObjets as $o) {
            if ((int) $o['id'] === $objetProposeId) {
                $ok = true;
                break;
            }
        }
        if (!$ok) {
            Flight::halt(400, 'Objet propose invalide');
            return;
        }

        EchangeModel::create($objetDemandeId, $objetProposeId, $userId, (int) $objetDemande['user_id']);
        Flight::redirect('/echanges');
    }

    public function accepter($id): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        $e = EchangeModel::find((int) $id);
        if (!$e || (int) $e['proprietaire_user_id'] !== $userId) {
            Flight::halt(403, 'Acces refuse');
            return;
        }

        EchangeModel::accept((int) $id);
        Flight::redirect('/echanges');
    }

    public function refuser($id): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        $e = EchangeModel::find((int) $id);
        if (!$e || (int) $e['proprietaire_user_id'] !== $userId) {
            Flight::halt(403, 'Acces refuse');
            return;
        }

        EchangeModel::refuse((int) $id);
        Flight::redirect('/echanges');
    }
}
