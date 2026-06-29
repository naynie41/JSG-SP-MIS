#!/bin/sh
# Install node deps on first run (fresh clone / bind mount) then exec the CMD.
set -e

cd /app

if [ ! -d node_modules ] || [ ! -f node_modules/.install-stamp ]; then
    echo "[entrypoint] Installing npm dependencies..."
    npm install
    touch node_modules/.install-stamp
fi

if [ ! -f .env ] && [ -f .env.example ]; then
    echo "[entrypoint] No .env found, copying from .env.example"
    cp .env.example .env
fi

echo "[entrypoint] Starting: $*"
exec "$@"
