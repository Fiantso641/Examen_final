<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class DonModel
{
    public static function all(): array
    {
        $db = Flight::db();
        $sql = 'SELECT d.*,
                       (d.quantite - COALESCE(SUM(a.quantite_attribuee),0)) AS quantite_restante
                FROM dons d
                LEFT JOIN allocations a ON a.don_id = d.id
                GROUP BY d.id
                ORDER BY d.date_don ASC, d.id ASC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $type, string $libelle, float $prixUnitaire, float $quantite, string $dateDon): int
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO dons (type, libelle, prix_unitaire, quantite, date_don) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$type, $libelle, $prixUnitaire, $quantite, $dateDon]);
        return (int) $db->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM dons WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function listOpenForDispatch(): array
    {
        $db = Flight::db();
        $sql = "SELECT d.*, (d.quantite - COALESCE(SUM(a.quantite_attribuee),0)) AS quantite_restante
                FROM dons d
                LEFT JOIN allocations a ON a.don_id = d.id
                GROUP BY d.id
                HAVING quantite_restante > 0
                ORDER BY d.date_don ASC, d.id ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
