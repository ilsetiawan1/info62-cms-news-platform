#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on Render's dynamic PORT
sed -ri "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Create storage symlink if not present
php artisan storage:link || true

# Cache Laravel config, routes, and views using runtime environment variables
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: run migrations & seeders automatically when enabled via env vars
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${RUN_SEED:-false}" = "true" ]; then
    php artisan db:seed --force
fi

exec apache2-foreground
