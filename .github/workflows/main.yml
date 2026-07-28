#!/usr/bin/env bash

# Automated deployment script for VPS
# Usage:
#   chmod +x deploy.sh
#   ./deploy.sh [branch] [--seed]
# Example:
#   ./deploy.sh main --seed

set -euo pipefail
IFS=$'\n\t'

APP_DIR="$(pwd)"
BRANCH="${1:-main}"
SEED=false

if [[ "${2:-}" == "--seed" ]]; then
  SEED=true
fi

echo "[deploy] Starting deployment in ${APP_DIR}"
echo "[deploy] Branch: ${BRANCH}"

echo "[deploy] Fetching latest changes..."
git fetch --all --prune

echo "[deploy] Checking out branch ${BRANCH}..."
git checkout "${BRANCH}"

echo "[deploy] Pulling latest from origin/${BRANCH}..."
git pull origin "${BRANCH}"

echo "[deploy] Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --prefer-dist

if [[ -f package-lock.json || -f yarn.lock ]]; then
  echo "[deploy] Installing Node dependencies..."
  npm install
fi

echo "[deploy] Building frontend assets..."
npm run production

echo "[deploy] Running database migrations..."
php artisan migrate --force

if [[ "$SEED" == true ]]; then
  echo "[deploy] Running database seeders..."
  php artisan db:seed --force
fi

echo "[deploy] Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[deploy] Setting storage and cache permissions..."
chmod -R ug+rwx storage bootstrap/cache || true

echo "[deploy] Deployment completed successfully."
echo "[deploy] Remember to set APP_ENV=production and APP_DEBUG=false in .env"
