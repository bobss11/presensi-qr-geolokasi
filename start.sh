#!/bin/sh

echo "==> Menjalankan migrasi database..."
php artisan migrate --force || true

echo "==> Menjalankan seeder database..."
php artisan db:seed --force || true

echo "==> Membersihkan cache konfigurasi Laravel..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

PORT="${PORT:-8080}"
echo "==> Menyiapkan port Nginx (PORT: ${PORT})..."
if [ "$PORT" != "8080" ]; then
    echo "==> Menambahkan listen port ${PORT} ke Nginx..."
    sed -i "s/listen 8080 default_server;/listen 8080;\n    listen ${PORT} default_server;/" /etc/nginx/http.d/default.conf
fi

echo "==> Memeriksa sintaks Nginx..."
nginx -t

echo "==> Menjalankan PHP-FPM di background..."
php-fpm &

sleep 1

echo "==> Menjalankan Nginx di foreground..."
exec nginx -g 'daemon off;'
