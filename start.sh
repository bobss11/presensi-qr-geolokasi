#!/bin/sh

# Migrations and seeding
php artisan migrate --force || true
php artisan db:seed --force || true
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Configure Nginx port from environment
PORT="${PORT:-8080}"
sed -i "s/listen 8080/listen $PORT/" /etc/nginx/http.d/default.conf

# Start PHP-FPM daemon
php-fpm -D

# Start Nginx in foreground (keeps container alive)
exec nginx -g 'daemon off;'
