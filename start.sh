#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "Checking prerequisites..."

if ! command -v composer &>/dev/null; then
  echo "[ERROR] Composer not found. Please install Composer first:" >&2
  echo "  https://getcomposer.org/download/" >&2
  exit 1
fi

if ! command -v php &>/dev/null; then
  echo "[WARN] PHP not found. Make sure php is in your PATH."
fi

if ! command -v npm &>/dev/null; then
  echo "[WARN] npm not found. The UI assets will not be built automatically."
fi

echo "Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  echo ".env belum ada - menyalin dari .env.example..."
  cp .env.example .env
fi

echo "Generating application key..."
php artisan key:generate --force

if [ ! -f database/database.sqlite ]; then
  echo "Membuat database SQLite kosong..."
  touch database/database.sqlite
fi

echo "Running database migrations..."
php artisan migrate --force

echo "Installing JS dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Starting development server at http://127.0.0.1:8000"
echo "Press Ctrl+C to stop."
php artisan serve --host=127.0.0.1 --port=8000 --no-reload
