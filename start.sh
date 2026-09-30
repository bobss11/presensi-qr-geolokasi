#!/bin/sh
set -e

echo "==> Menjalankan migrasi database..."
php artisan migrate --force || true

echo "==> Menjalankan seeder database..."
php artisan db:seed --force || true

echo "==> Cache konfigurasi Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

PORT="${PORT:-8080}"
echo "==> Mengatur Nginx mendengarkan pada port ${PORT}..."
sed -i "s/listen [0-9]*/listen ${PORT}/" /etc/nginx/http.d/default.conf

# Validasi konfigurasi Nginx
nginx -t

echo "==> Menjalankan PHP-FPM..."
php-fpm -D

echo "==> Menjalankan Nginx di foreground pada port ${PORT}..."
exec nginx -g 'daemon off;'
