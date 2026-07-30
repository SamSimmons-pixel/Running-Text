#!/bin/bash
set -e

echo "[Entrypoint] Fixing storage permissions..."
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

echo "[Entrypoint] Waiting for database to be ready..."
sleep 2

echo "[Entrypoint] Running database migrations..."
php artisan migrate --force

echo "[Entrypoint] Seeding initial data (skipped if already seeded)..."
php artisan db:seed --force

echo "[Entrypoint] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "[Entrypoint] Clearing compiled views..."
php artisan view:clear

# If a command was passed to the container (e.g. queue:work or reverb:start), run it.
# Otherwise, default to starting Apache web server.
if [ "$#" -gt 0 ]; then
    echo "[Entrypoint] Executing custom command: $@"
    exec "$@"
else
    echo "[Entrypoint] Starting Apache web server..."
    exec apache2-foreground
fi
