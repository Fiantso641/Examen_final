<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class CategoryModel
{
    public static function all(): array
    {
        $db = Flight::db();
        return $db->query('SELECT * FROM categories ORDER BY nom')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $nom): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO categories (nom) VALUES (?)');
        $stmt->execute([$nom]);
    }

    public static function update(int $id, string $nom): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('UPDATE categories SET nom = ? WHERE id = ?');
        $stmt->execute([$nom, $id]);
    }

    public static function delete(int $id): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function find(int $id): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
