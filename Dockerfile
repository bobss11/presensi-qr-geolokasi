FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    linux-headers \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath opcache pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Nginx serves HTTP and proxies PHP requests to PHP-FPM over a Unix socket
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/php-fpm/zzz-socket.conf /usr/local/etc/php-fpm.d/zzz-socket.conf
RUN mkdir -p /run/nginx

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && chmod +x /var/www/start.sh

ENV PORT=8080
EXPOSE 8080

CMD ["sh", "start.sh"]
