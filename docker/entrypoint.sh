#!/bin/bash
set -e

echo "[Entrypoint] Fixing storage permissions..."
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

echo "[Entrypoint] Waiting for database to be ready..."
# Extra safety wait in case healthcheck passed but MySQL not fully ready
sleep 2

echo "[Entrypoint] Running database migrations..."
php artisan migrate

echo "[Entrypoint] Seeding initial data (skipped if already seeded)..."
php artisan db:seed

echo "[Entrypoint] Creating storage symlink..."
php artisan storage:link

# echo "[Entrypoint] Clearing compiled views..."
# php artisan view:clear

# echo "[Entrypoint] Starting Apache..."
# exec apache2-foreground
