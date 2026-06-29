#!/usr/bin/env bash
# Entrypoint for the SP-MIS api/worker containers.
# Makes a fresh clone "just work": prepares .env, installs deps, waits for the
# database, and (for the api role) runs migrations before handing off to CMD.
#
# Privilege model: this script starts as root to fix ownership of mounted
# volumes, runs all prep as the unprivileged www-data user (so generated files
# are owned correctly), then:
#   - php-fpm  -> exec as root (master needs root; pool workers drop to www-data)
#   - anything -> exec as www-data via su-exec (e.g. queue:work)
set -euo pipefail

cd /var/www/html

# Mounted volumes (vendor named volume, bind-mounted storage) come up root-owned.
if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data \
        /var/www/html/vendor \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache 2>/dev/null || true
fi

run() {
    if [ "$(id -u)" = "0" ]; then
        su-exec www-data "$@"
    else
        "$@"
    fi
}

# 1. Ensure an .env exists.
if [ ! -f .env ]; then
    echo "[entrypoint] No .env found, copying from .env.example"
    run cp .env.example .env
fi

# 2. Install PHP dependencies if vendor/ is missing (fresh clone / bind mount).
if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] Installing composer dependencies..."
    run composer install --no-interaction --prefer-dist --no-progress
fi

# 3. Generate APP_KEY if not set.
if ! grep -q '^APP_KEY=base64:' .env; then
    echo "[entrypoint] Generating application key..."
    run php artisan key:generate --force
fi

# 4. Wait for PostgreSQL to accept connections.
echo "[entrypoint] Waiting for PostgreSQL at ${DB_HOST:-postgres}:${DB_PORT:-5432}..."
until pg_isready -h "${DB_HOST:-postgres}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-spmis}" >/dev/null 2>&1; do
    sleep 2
done
echo "[entrypoint] PostgreSQL is ready."

# 5. The api role runs migrations; the worker role does not (avoids races).
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "[entrypoint] Running migrations..."
    run php artisan migrate --force
fi

# Cache config/routes only outside local for speed; in local keep them dynamic.
if [ "${APP_ENV:-local}" != "local" ]; then
    run php artisan config:cache
    run php artisan route:cache
fi

echo "[entrypoint] Starting: $*"

# php-fpm master must run as root (workers drop to www-data via the pool config);
# everything else runs unprivileged.
if [ "$1" = "php-fpm" ] || [ "$(id -u)" != "0" ]; then
    exec "$@"
fi

exec su-exec www-data "$@"
