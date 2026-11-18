<?php

declare(strict_types=1);

namespace Tests;

use App\Security\CsrfTokenManager;
use App\Security\SessionAuth;
use PHPUnit\Framework\TestCase;

final class CsrfAndAuthTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_ENV['ADMIN_LOGIN'] = 'admin';
        $_ENV['ADMIN_PASSWORD'] = 'secret';
        $_SERVER['ADMIN_LOGIN'] = 'admin';
        $_SERVER['ADMIN_PASSWORD'] = 'secret';
        putenv('ADMIN_LOGIN=admin');
        putenv('ADMIN_PASSWORD=secret');
    }

    public function testCsrfRejectsInvalidToken(): void
    {
        $csrf = new CsrfTokenManager();
        $token = $csrf->token();

        self::assertTrue($csrf->isValid($token));
        self::assertFalse($csrf->isValid('invalid-token'));
        self::assertFalse($csrf->isValid(null));
    }

    public function testAuthAcceptsValidCredentials(): void
    {
        $auth = new SessionAuth();

        self::assertFalse($auth->check());
        self::assertTrue($auth->attempt('admin', 'secret'));
        self::assertTrue($auth->check());

        $auth->logout();
        self::assertFalse($auth->check());
    }

    public function testAuthRejectsInvalidCredentials(): void
    {
        $auth = new SessionAuth();

        self::assertFalse($auth->attempt('admin', 'wrong'));
        self::assertFalse($auth->check());
    }
}
