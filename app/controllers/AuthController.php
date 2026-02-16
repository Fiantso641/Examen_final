<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\UserModel;
use app\utils\Auth;
use Flight;

class AuthController
{
    public function showLogin(): void
    {
        Flight::render('auth/login', [
            'default_email' => 'jean@example.com',
        ]);
    }

    public function login(): void
    {
        $email = trim((string) (Flight::request()->data->email ?? ''));
        $password = (string) (Flight::request()->data->password ?? '');

        $user = UserModel::findByEmail($email);
        if (!$user || !hash_equals((string) $user['password'], $password)) {
            Flight::render('auth/login', [
                'default_email' => $email,
                'error' => 'Identifiants invalides',
            ]);
            return;
        }

        Auth::loginUser((int) $user['id']);
        Flight::redirect('/');
    }

    public function logout(): void
    {
        Auth::logoutUser();
        Flight::redirect('/');
    }

    public function showRegister(): void
    {
        Flight::render('auth/register');
    }

    public function register(): void
    {
        $nom = trim((string) (Flight::request()->data->nom ?? ''));
        $prenom = trim((string) (Flight::request()->data->prenom ?? ''));
        $email = trim((string) (Flight::request()->data->email ?? ''));
        $password = (string) (Flight::request()->data->password ?? '');

        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            Flight::render('auth/register', [
                'error' => 'Tous les champs sont obligatoires',
                'old' => compact('nom','prenom','email'),
            ]);
            return;
        }

        if (UserModel::findByEmail($email)) {
            Flight::render('auth/register', [
                'error' => 'Email deja utilise',
                'old' => compact('nom','prenom','email'),
            ]);
            return;
        }

        $id = UserModel::create($nom, $prenom, $email, $password);
        Auth::loginUser($id);
        Flight::redirect('/');
    }
}
