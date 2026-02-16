<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class DispatchModel
{
    public static function resetAllocations(): void
    {
        $db = Flight::db();
        $db->exec('DELETE FROM allocations');
    }

    public static function allocate(int $donId, int $besoinId, float $quantite): void
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO allocations (don_id, besoin_id, quantite_attribuee) VALUES (?, ?, ?)');
        $stmt->execute([$donId, $besoinId, $quantite]);
    }

    public static function dashboardRows(): array
    {
        $db = Flight::db();
        $sql = "SELECT v.id AS ville_id, v.nom AS ville_nom, v.region,
                       b.id AS besoin_id, b.type, b.libelle, b.prix_unitaire, b.quantite,
                       COALESCE(SUM(a.quantite_attribuee),0) AS quantite_attribuee
                FROM villes v
                LEFT JOIN besoins b ON b.ville_id = v.id
                LEFT JOIN allocations a ON a.besoin_id = b.id
                GROUP BY v.id, b.id
                ORDER BY v.nom ASC, b.created_at ASC, b.id ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
