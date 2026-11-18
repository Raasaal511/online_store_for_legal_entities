<?php

declare(strict_types=1);

use App\Config\Env;

$basePath = dirname(__DIR__);
require $basePath . '/vendor/autoload.php';

Env::load($basePath);

$host = Env::get('DB_HOST', '127.0.0.1');
$port = Env::get('DB_PORT', '3306');
$database = Env::require('DB_DATABASE');
$username = Env::require('DB_USERNAME');
$password = Env::get('DB_PASSWORD', '');

// Prefer root for schema bootstrap when provided.
$setupUser = Env::get('DB_SETUP_USERNAME', $username);
$setupPassword = Env::get('DB_SETUP_PASSWORD', $password);

$pdo = new PDO(
    sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port),
    (string) $setupUser,
    (string) $setupPassword,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
    ]
);

$schema = file_get_contents($basePath . '/sql/schema.sql');
$seed = file_get_contents($basePath . '/sql/seed.sql');

if ($schema === false || $seed === false) {
    fwrite(STDERR, "Cannot read SQL files\n");
    exit(1);
}

$pdo->exec($schema);
$pdo->exec($seed);

echo "Database `{$database}` is ready with demo applications.\n";
