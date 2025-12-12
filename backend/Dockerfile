# Base PHP FPM
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libicu-dev libzip-dev zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias para Yii2
RUN docker-php-ext-install pdo pdo_mysql mbstring xml intl zip gd

# Instalar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copiar configuração do Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instalar Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html/backend

# Copiar o composer.json da raiz
COPY composer.json composer.lock ./

# Instalar dependências
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copiar todo o backend (incluindo yi/)
COPY backend/ ./
