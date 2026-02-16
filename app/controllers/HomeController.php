<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\CategoryModel;
use app\models\ObjetModel;
use app\utils\Auth;
use Flight;

class HomeController
{
    public function index(): void
    {
        $userId = Auth::userId();
        $categories = CategoryModel::all();

        $q = Flight::request()->query->q ?? null;
        $cat = Flight::request()->query->cat ?? null;
        $catId = ($cat !== null && $cat !== '') ? (int) $cat : null;

        $objets = [];
        if ($userId !== null) {
            $objets = ObjetModel::listOthers($userId, is_string($q) ? $q : null, $catId);
        }

        Flight::render('home', [
            'user_id' => $userId,
            'categories' => $categories,
            'objets' => $objets,
            'q' => is_string($q) ? $q : '',
            'cat' => $catId,
        ]);
    }
}
