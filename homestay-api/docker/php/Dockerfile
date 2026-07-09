# ===========================
# Stage 1: Build dependencies
# ===========================
FROM composer:2 AS vendor

WORKDIR /app

# Copy toàn bộ project
COPY ../.. /app

# Cài dependencies (không include dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist


# ===========================
# Stage 2: App image (Production)
# ===========================
FROM php:8.2-fpm

# Cài package cần cho build extension
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev zlib1g-dev libicu-dev g++ \
    supervisor cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
       pdo_mysql \
       mbstring \
       exif \
       pcntl \
       bcmath \
       gd \
       zip \
       intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy project + composer
COPY --chown=www-data:www-data ../.. /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy supervisor config
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy cron file
COPY docker/php/crontab /etc/cron.d/artisan-schedule
RUN chmod 0644 /etc/cron.d/artisan-schedule

# Tạo log supervisor
RUN mkdir -p /var/log/supervisor

# Set quyền storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod 600 /var/www/html/storage/oauth-private.key /var/www/html/storage/oauth-public.key

# Entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Chạy container bằng root để cron daemon chạy, php-fpm & worker vẫn chạy www-data
USER root

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
EXPOSE 9000
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
