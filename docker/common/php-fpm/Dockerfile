# Stage 1 — PHP + Node + Composer for Laravel & Vite
FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Node (18 LTS) + npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Ensure PHP-FPM user owns files
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]