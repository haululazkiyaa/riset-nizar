FROM dunglas/frankenphp:1-php8.4

WORKDIR /app

RUN install-php-extensions pdo_pgsql redis opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /app

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/app/frankenphp/Caddyfile"]