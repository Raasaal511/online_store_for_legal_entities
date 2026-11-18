<?php

declare(strict_types=1);

namespace App\Security;

use App\Config\Env;

final class SessionAuth
{
    private const SESSION_FLAG = 'admin_authenticated';

    public function attempt(string $login, string $password): bool
    {
        $expectedLogin = Env::require('ADMIN_LOGIN');
        $expectedPassword = Env::require('ADMIN_PASSWORD');

        $loginOk = hash_equals($expectedLogin, $login);
        $passwordOk = hash_equals($expectedPassword, $password);

        if ($loginOk && $passwordOk) {
            $_SESSION[self::SESSION_FLAG] = true;
            $this->regenerate();
            return true;
        }

        return false;
    }

    public function check(): bool
    {
        return !empty($_SESSION[self::SESSION_FLAG]);
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_FLAG]);
        $this->regenerate();
    }

    private function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
