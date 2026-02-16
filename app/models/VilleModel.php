<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class VilleModel
{
    public static function all(): array
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT * FROM villes ORDER BY nom ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $nom, ?string $region): int
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO villes (nom, region) VALUES (?, ?)');
        $stmt->execute([$nom, $region]);
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, string $nom, ?string $region): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('UPDATE villes SET nom = ?, region = ? WHERE id = ?');
        $stmt->execute([$nom, $region, $id]);
    }

    public static function delete(int $id): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM villes WHERE id = ?');
        $stmt->execute([$id]);
    }
}
