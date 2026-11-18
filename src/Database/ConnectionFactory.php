<?php

declare(strict_types=1);

namespace App\Database;

use App\Config\Env;
use PDO;

final class ConnectionFactory
{
    public static function createFromEnv(): PDO
    {
        $driver = Env::get('DB_DRIVER', 'mysql');

        if ($driver === 'sqlite') {
            $path = Env::require('DB_DATABASE');
            $pdo = new PDO('sqlite:' . $path);
            self::configure($pdo);
            return $pdo;
        }

        $host = Env::get('DB_HOST', '127.0.0.1');
        $port = Env::get('DB_PORT', '3306');
        $database = Env::require('DB_DATABASE');
        $username = Env::require('DB_USERNAME');
        $password = Env::get('DB_PASSWORD', '');

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);
        $pdo = new PDO($dsn, $username, $password);
        self::configure($pdo);

        return $pdo;
    }

    public static function createSqliteMemory(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        self::configure($pdo);
        return $pdo;
    }

    private static function configure(PDO $pdo): void
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}
