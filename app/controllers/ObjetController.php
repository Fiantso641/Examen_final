<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\CategoryModel;
use app\models\ObjetModel;
use app\utils\Auth;
use Flight;

class ObjetController
{
    public function mine(): void
    {
        Auth::requireUser();
        $userId = Auth::userId();

        Flight::render('objets/mine', [
            'categories' => CategoryModel::all(),
            'objets' => ObjetModel::listMine((int) $userId),
        ]);
    }

    public function store(): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();

        $categorieId = (int) (Flight::request()->data->categorie_id ?? 0);
        $titre = trim((string) (Flight::request()->data->titre ?? ''));
        $description = trim((string) (Flight::request()->data->description ?? ''));
        $prix = (float) (Flight::request()->data->prix_estime ?? 0);

        if ($categorieId <= 0 || $titre === '') {
            Flight::redirect('/mes-objets');
            return;
        }

        $objetId = ObjetModel::create($userId, $categorieId, $titre, $description, $prix);
        $this->handleUploadPhotos($objetId);

        Flight::redirect('/mes-objets');
    }

    public function update($id): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        $objetId = (int) $id;

        $categorieId = (int) (Flight::request()->data->categorie_id ?? 0);
        $titre = trim((string) (Flight::request()->data->titre ?? ''));
        $description = trim((string) (Flight::request()->data->description ?? ''));
        $prix = (float) (Flight::request()->data->prix_estime ?? 0);

        if ($categorieId > 0 && $titre !== '') {
            ObjetModel::update($objetId, $userId, $categorieId, $titre, $description, $prix);
            $this->handleUploadPhotos($objetId);
        }

        Flight::redirect('/mes-objets');
    }

    public function delete($id): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        ObjetModel::delete((int) $id, $userId);
        Flight::redirect('/mes-objets');
    }

    public function list(): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();

        $q = Flight::request()->query->q ?? null;
        $cat = Flight::request()->query->cat ?? null;
        $catId = ($cat !== null && $cat !== '') ? (int) $cat : null;

        Flight::render('objets/list', [
            'categories' => CategoryModel::all(),
            'objets' => ObjetModel::listOthers($userId, is_string($q) ? $q : null, $catId),
            'q' => is_string($q) ? $q : '',
            'cat' => $catId,
        ]);
    }

    public function show($id): void
    {
        Auth::requireUser();
        $userId = (int) Auth::userId();
        $objet = ObjetModel::findById((int) $id);
        if (!$objet) {
            Flight::halt(404, 'Objet introuvable');
            return;
        }

        $photos = ObjetModel::photos((int) $id);
        $history = ObjetModel::ownershipHistory((int) $id);
        $mesObjets = ObjetModel::listMine($userId);

        Flight::render('objets/show', [
            'objet' => $objet,
            'photos' => $photos,
            'history' => $history,
            'mes_objets' => $mesObjets,
            'user_id' => $userId,
        ]);
    }

    private function handleUploadPhotos(int $objetId): void
    {
        if (!isset($_FILES['photos'])) {
            return;
        }

        $files = $_FILES['photos'];
        if (!is_array($files['name'])) {
            return;
        }

        $uploadDir = __DIR__ . '/../../public/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            $tmp = $files['tmp_name'][$i];
            $original = (string) $files['name'][$i];
            $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) {
                continue;
            }

            $name = 'obj_' . $objetId . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $dest = $uploadDir . '/' . $name;
            if (move_uploaded_file($tmp, $dest)) {
                ObjetModel::addPhoto($objetId, '/uploads/' . $name);
            }
        }
    }
}
