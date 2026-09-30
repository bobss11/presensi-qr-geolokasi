#!/bin/sh

echo "==> Menjalankan migrasi database..."
php artisan migrate --force || true

echo "==> Menjalankan seeder database..."
php artisan db:seed --force || true

echo "==> Optimasi konfigurasi Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

PORT="${PORT:-8080}"
echo "==> Menjalankan server pada port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
