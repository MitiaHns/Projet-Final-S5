#!/usr/bin/env bash
set -e

cd /var/www/html

# Install PHP deps (dev-friendly; in prod you'd bake vendor into the image)
if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist
fi

# Ensure env
if [ ! -f ".env" ]; then
  cp .env.example .env
fi

# App key
php artisan key:generate --force || true

# Wait for Postgres
if [ -n "$ADMIN_DB_HOST" ]; then
  echo "Waiting for Postgres..."
  for i in {1..30}; do
    if php -r '$h=getenv("ADMIN_DB_HOST");$p=getenv("ADMIN_DB_PORT");$d=getenv("ADMIN_DB_DATABASE");$u=getenv("ADMIN_DB_USERNAME");$pw=getenv("ADMIN_DB_PASSWORD");new PDO("pgsql:host={$h};port={$p};dbname={$d}",$u,$pw);' >/dev/null 2>&1; then
      break
    fi
    sleep 1
  done
fi

# Migrate + seed Admin DB
php artisan migrate --force || true
php artisan db:seed --force || true

exec "$@"
