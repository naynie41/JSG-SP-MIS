#!/bin/sh
# Production entrypoint for the SP-MIS api/worker containers. Runs entirely as the
# unprivileged www-data user (set via USER in the Dockerfile). Unlike the dev
# entrypoint it NEVER installs dependencies, copies an .env, or generates an app
# key at runtime — production config comes from the container environment and the
# APP_KEY is a managed secret (a fresh key would orphan all encrypted data/backups).
set -eu

cd /var/www/html

# APP_KEY must be provided by the environment. Fail fast rather than boot insecure.
if [ -z "${APP_KEY:-}" ]; then
    echo "[entrypoint] FATAL: APP_KEY is not set — refusing to start." >&2
    exit 1
fi

# Wait for PostgreSQL to accept connections.
echo "[entrypoint] Waiting for PostgreSQL at ${DB_HOST:-postgres}:${DB_PORT:-5432}..."
until pg_isready -h "${DB_HOST:-postgres}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-spmis}" >/dev/null 2>&1; do
    sleep 2
done
echo "[entrypoint] PostgreSQL is ready."

# The api role owns schema migration; the worker role does not (avoids races).
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "[entrypoint] Running migrations..."
    php artisan migrate --force
fi

# Warm the framework caches from the current container environment (idempotent).
php artisan config:cache
php artisan route:cache
php artisan event:cache

echo "[entrypoint] Starting: $*"
exec "$@"
