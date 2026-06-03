#!/bin/sh
set -e

# Generate APP_KEY only if it is not provided via environment.
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is empty — generating an ephemeral key (set APP_KEY env for persistent sessions)."
    php artisan key:generate --force
fi

# Symlink public storage for uploaded images.
php artisan storage:link || true

# Apply database schema. Runs every boot but is idempotent.
php artisan migrate --force

# Seed demo data once (set SEED_DEMO=true on first deploy only).
if [ "$SEED_DEMO" = "true" ]; then
    php artisan db:seed --force || true
fi

# Cache framework config/routes for performance.
php artisan config:cache
php artisan route:cache

# Serve the application on the platform-provided port.
exec php artisan serve --host 0.0.0.0 --port "${PORT:-8000}"
