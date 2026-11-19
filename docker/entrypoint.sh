#!/bin/sh
set -e

echo "Waiting for MySQL..."
i=0
until php -r '
try {
  new PDO(
    sprintf("mysql:host=%s;port=%s;charset=utf8mb4", getenv("DB_HOST") ?: "mysql", getenv("DB_PORT") ?: "3306"),
    getenv("DB_SETUP_USERNAME") ?: "root",
    getenv("DB_SETUP_PASSWORD") ?: "root"
  );
  exit(0);
} catch (Throwable $e) {
  exit(1);
}
'; do
  i=$((i + 1))
  if [ "$i" -gt 60 ]; then
    echo "MySQL is not available"
    exit 1
  fi
  sleep 2
done

php bin/setup-db.php

exec apache2-foreground
