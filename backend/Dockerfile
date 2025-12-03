# backend/Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
  && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

WORKDIR /var/www/html

# composer install will be done on host or via composer container
