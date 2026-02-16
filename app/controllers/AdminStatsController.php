<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\EchangeModel;
use app\models\UserModel;
use app\utils\Auth;
use Flight;

class AdminStatsController
{
    public function dashboard(): void
    {
        Auth::requireAdmin();
        Flight::render('admin/dashboard', [
            'nb_users' => UserModel::countAll(),
            'nb_echanges' => EchangeModel::countAccepted(),
        ]);
    }
}
