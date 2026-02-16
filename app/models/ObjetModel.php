<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class ObjetModel
{
    public static function create(int $userId, int $categorieId, string $titre, string $description, float $prixEstime): int
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO objets (user_id, categorie_id, titre, description, prix_estime) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $categorieId, $titre, $description, $prixEstime]);
        $objetId = (int) $db->lastInsertId();

        $stmtHist = $db->prepare('INSERT INTO objet_ownership_history (objet_id, user_id, acquired_at, echange_id) VALUES (?, ?, NOW(), NULL)');
        $stmtHist->execute([$objetId, $userId]);

        return $objetId;
    }

    public static function update(int $objetId, int $userId, int $categorieId, string $titre, string $description, float $prixEstime): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('UPDATE objets SET categorie_id = ?, titre = ?, description = ?, prix_estime = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$categorieId, $titre, $description, $prixEstime, $objetId, $userId]);
    }

    public static function delete(int $objetId, int $userId): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM objets WHERE id = ? AND user_id = ?');
        $stmt->execute([$objetId, $userId]);
    }

    public static function findById(int $id): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT o.*, c.nom AS categorie_nom, u.nom AS owner_nom, u.prenom AS owner_prenom
                              FROM objets o
                              JOIN categories c ON c.id = o.categorie_id
                              JOIN users u ON u.id = o.user_id
                              WHERE o.id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function listMine(int $userId): array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT o.*, c.nom AS categorie_nom
                              FROM objets o
                              JOIN categories c ON c.id = o.categorie_id
                              WHERE o.user_id = ?
                              ORDER BY o.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listOthers(int $userId, ?string $q, ?int $categorieId): array
    {
        $db = Flight::db();
        $where = ['o.user_id <> ?','o.is_active = 1'];
        $params = [$userId];

        if ($q !== null && trim($q) !== '') {
            $where[] = 'o.titre LIKE ?';
            $params[] = '%' . trim($q) . '%';
        }
        if ($categorieId !== null) {
            $where[] = 'o.categorie_id = ?';
            $params[] = $categorieId;
        }

        $sql = 'SELECT o.*, c.nom AS categorie_nom, u.nom AS owner_nom, u.prenom AS owner_prenom
                FROM objets o
                JOIN categories c ON c.id = o.categorie_id
                JOIN users u ON u.id = o.user_id
                WHERE ' . implode(' AND ', $where) . '
                ORDER BY o.created_at DESC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addPhoto(int $objetId, string $filePath): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO objet_photos (objet_id, file_path) VALUES (?, ?)');
        $stmt->execute([$objetId, $filePath]);
    }

    public static function photos(int $objetId): array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM objet_photos WHERE objet_id = ? ORDER BY id ASC');
        $stmt->execute([$objetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ownershipHistory(int $objetId): array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT h.acquired_at, u.nom, u.prenom
                              FROM objet_ownership_history h
                              JOIN users u ON u.id = h.user_id
                              WHERE h.objet_id = ?
                              ORDER BY h.acquired_at ASC, h.id ASC');
        $stmt->execute([$objetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
