FROM php:8.2-fpm

WORKDIR /var/www/html

# System deps + PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip \
    libzip-dev libpq-dev \
    autoconf g++ make pkg-config \
    && docker-php-ext-install pdo_pgsql zip \
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy code (for prod builds; in dev docker-compose mounts it)
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
