<?php

declare(strict_types=1);

namespace App;

use App\Config\Env;
use App\Database\ConnectionFactory;
use App\Http\View;
use App\Repository\PdoApplicationRepository;
use App\Security\CsrfTokenManager;
use App\Security\SessionAuth;
use App\Service\ApplicationService;
use PDO;

final class Bootstrap
{
    private static ?PDO $pdo = null;

    public static function init(string $basePath): void
    {
        Env::load($basePath);

        $sessionName = Env::get('SESSION_NAME', 'snabplus_session');
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name((string) $sessionName);
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }
    }

    public static function pdo(): PDO
    {
        return self::$pdo ??= ConnectionFactory::createFromEnv();
    }

    public static function setPdo(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    public static function applicationService(): ApplicationService
    {
        return new ApplicationService(new PdoApplicationRepository(self::pdo()));
    }

    public static function csrf(): CsrfTokenManager
    {
        return new CsrfTokenManager();
    }

    public static function auth(): SessionAuth
    {
        return new SessionAuth();
    }

    public static function view(string $basePath): View
    {
        return new View($basePath . '/templates');
    }
}
