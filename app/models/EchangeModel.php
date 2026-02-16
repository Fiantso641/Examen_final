<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class EchangeModel
{
    public static function create(int $objetDemandeId, int $objetProposeId, int $proposerUserId, int $proprietaireUserId): int
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO echanges (objet_demande_id, objet_propose_id, proposer_user_id, proprietaire_user_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$objetDemandeId, $objetProposeId, $proposerUserId, $proprietaireUserId]);
        return (int) $db->lastInsertId();
    }

    public static function listForUser(int $userId): array
    {
        $db = Flight::db();
        $stmt = $db->prepare(
            "SELECT e.*, 
                    od.titre AS objet_demande_titre,
                    op.titre AS objet_propose_titre,
                    up.nom AS proposer_nom, up.prenom AS proposer_prenom,
                    uo.nom AS owner_nom, uo.prenom AS owner_prenom
             FROM echanges e
             JOIN objets od ON od.id = e.objet_demande_id
             JOIN objets op ON op.id = e.objet_propose_id
             JOIN users up ON up.id = e.proposer_user_id
             JOIN users uo ON uo.id = e.proprietaire_user_id
             WHERE e.proposer_user_id = ? OR e.proprietaire_user_id = ?
             ORDER BY e.created_at DESC"
        );
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM echanges WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function countAccepted(): int
    {
        $db = Flight::db();
        $stmt = $db->query("SELECT COUNT(*) FROM echanges WHERE statut = 'accepte'");
        return (int) $stmt->fetchColumn();
    }

    public static function accept(int $echangeId): void
    {
        $db = Flight::db();
        $db->beginTransaction();

        $stmt = $db->prepare('SELECT * FROM echanges WHERE id = ? FOR UPDATE');
        $stmt->execute([$echangeId]);
        $e = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$e) {
            $db->rollBack();
            throw new \RuntimeException('Echange introuvable');
        }
        if ($e['statut'] !== 'propose') {
            $db->rollBack();
            throw new \RuntimeException('Echange deja traite');
        }

        $stmtU1 = $db->prepare('UPDATE objets SET user_id = ? WHERE id = ?');
        $stmtU2 = $db->prepare('UPDATE objets SET user_id = ? WHERE id = ?');

        $stmtU1->execute([(int) $e['proposer_user_id'], (int) $e['objet_demande_id']]);
        $stmtU2->execute([(int) $e['proprietaire_user_id'], (int) $e['objet_propose_id']]);

        $stmtHist = $db->prepare('INSERT INTO objet_ownership_history (objet_id, user_id, acquired_at, echange_id) VALUES (?, ?, NOW(), ?)');
        $stmtHist->execute([(int) $e['objet_demande_id'], (int) $e['proposer_user_id'], $echangeId]);
        $stmtHist->execute([(int) $e['objet_propose_id'], (int) $e['proprietaire_user_id'], $echangeId]);

        $stmtDecide = $db->prepare("UPDATE echanges SET statut = 'accepte', decided_at = NOW() WHERE id = ?");
        $stmtDecide->execute([$echangeId]);

        $db->commit();
    }

    public static function refuse(int $echangeId): void
    {
        $db = Flight::db();
        $stmt = $db->prepare("UPDATE echanges SET statut = 'refuse', decided_at = NOW() WHERE id = ? AND statut = 'propose'");
        $stmt->execute([$echangeId]);
    }
}
