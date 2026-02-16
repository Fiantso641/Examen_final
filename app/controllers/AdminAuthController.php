<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\AdminModel;
use app\utils\Auth;
use Flight;

class AdminAuthController
{
    public function showLogin(): void
    {
        Flight::render('admin/login', [
            'default_username' => 'admin',
        ]);
    }

    public function login(): void
    {
        $username = trim((string) (Flight::request()->data->username ?? ''));
        $password = (string) (Flight::request()->data->password ?? '');

        $admin = AdminModel::findByUsername($username);
        if (!$admin || !hash_equals((string) $admin['password'], $password)) {
            Flight::render('admin/login', [
                'default_username' => $username,
                'error' => 'Identifiants invalides',
            ]);
            return;
        }

        Auth::loginAdmin((int) $admin['id']);
        Flight::redirect('/admin');
    }

    public function logout(): void
    {
        Auth::logoutAdmin();
        Flight::redirect('/admin/login');
    }
}
