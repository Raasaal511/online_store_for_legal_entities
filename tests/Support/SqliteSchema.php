<?php

declare(strict_types=1);

namespace Tests\Support;

use PDO;

final class SqliteSchema
{
    public static function migrate(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE applications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                company_name TEXT NOT NULL,
                contact_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                email TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT \'new\',
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL
            )'
        );
    }
}
