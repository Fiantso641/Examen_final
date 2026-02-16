<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\CategoryModel;
use app\utils\Auth;
use Flight;

class AdminCategoryController
{
    public function index(): void
    {
        Auth::requireAdmin();
        Flight::render('admin/categories/index', [
            'categories' => CategoryModel::all(),
        ]);
    }

    public function store(): void
    {
        Auth::requireAdmin();
        $nom = trim((string) (Flight::request()->data->nom ?? ''));
        if ($nom !== '') {
            CategoryModel::create($nom);
        }
        Flight::redirect('/admin/categories');
    }

    public function update($id): void
    {
        Auth::requireAdmin();
        $nom = trim((string) (Flight::request()->data->nom ?? ''));
        if ($nom !== '') {
            CategoryModel::update((int) $id, $nom);
        }
        Flight::redirect('/admin/categories');
    }

    public function delete($id): void
    {
        Auth::requireAdmin();
        CategoryModel::delete((int) $id);
        Flight::redirect('/admin/categories');
    }
}
