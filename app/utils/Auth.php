<?php

declare(strict_types=1);

namespace app\utils;

use Flight;

class Auth
{
    public static function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function adminId(): ?int
    {
        return isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : null;
    }

    public static function requireUser(): void
    {
        if (self::userId() === null) {
            Flight::redirect('/login');
        }
    }

    public static function requireAdmin(): void
    {
        if (self::adminId() === null) {
            Flight::redirect('/admin/login');
        }
    }

    public static function loginUser(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    public static function logoutUser(): void
    {
        unset($_SESSION['user_id']);
    }

    public static function loginAdmin(int $adminId): void
    {
        $_SESSION['admin_id'] = $adminId;
    }

    public static function logoutAdmin(): void
    {
        unset($_SESSION['admin_id']);
    }
}
