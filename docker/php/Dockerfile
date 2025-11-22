FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# OPCACHE for performance
RUN docker-php-ext-install opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Laravel needs this folder for caching
RUN mkdir -p /var/www/html/storage/framework/cache/data

WORKDIR /var/www/html

# Optimize PHP-FPM for Laravel
#COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini