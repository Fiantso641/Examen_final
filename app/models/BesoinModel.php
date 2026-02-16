<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    public static function list(?int $villeId): array
    {
        $db = Flight::db();

        $where = [];
        $params = [];
        if ($villeId !== null) {
            $where[] = 'b.ville_id = ?';
            $params[] = $villeId;
        }

        $sql = 'SELECT b.*, v.nom AS ville_nom,
                       (b.quantite - COALESCE(SUM(a.quantite_attribuee),0)) AS quantite_restante
                FROM besoins b
                JOIN villes v ON v.id = b.ville_id
                LEFT JOIN allocations a ON a.besoin_id = b.id
                ' . (empty($where) ? '' : ('WHERE ' . implode(' AND ', $where))) . '
                GROUP BY b.id
                ORDER BY b.created_at ASC, b.id ASC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(int $villeId, string $type, string $libelle, float $prixUnitaire, float $quantite): int
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO besoins (ville_id, type, libelle, prix_unitaire, quantite) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$villeId, $type, $libelle, $prixUnitaire, $quantite]);
        return (int) $db->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM besoins WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function listOpenForDispatch(): array
    {
        $db = Flight::db();
        $sql = "SELECT b.*, (b.quantite - COALESCE(SUM(a.quantite_attribuee),0)) AS quantite_restante
                FROM besoins b
                LEFT JOIN allocations a ON a.besoin_id = b.id
                GROUP BY b.id
                HAVING quantite_restante > 0
                ORDER BY b.created_at ASC, b.id ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
