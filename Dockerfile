FROM dunglas/frankenphp:latest-php8.3-alpine

WORKDIR /app

# PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    pdo_sqlite \
    redis \
    pcov \
    opcache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Application
COPY . .
RUN composer dump-autoload --optimize

# Storage permissions
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
