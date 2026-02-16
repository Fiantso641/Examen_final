<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class UserModel
{
    public static function create(string $nom, string $prenom, string $email, string $password): int
    {
        $db = Flight::db();

        $stmt = $db->prepare('INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nom, $prenom, $email, $password]);
        return (int) $db->lastInsertId();
    }

    public static function findByEmail(string $email): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function findById(int $id): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function countAll(): int
    {
        $db = Flight::db();
        return (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
