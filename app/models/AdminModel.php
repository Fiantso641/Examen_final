<?php

declare(strict_types=1);

namespace app\models;

use Flight;
use PDO;

class AdminModel
{
    public static function findByUsername(string $username): ?array
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
