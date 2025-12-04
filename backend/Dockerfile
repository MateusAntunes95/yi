FROM php:8.2-fpm

# Instalar dependências do sistema para extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libicu-dev libzip-dev zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões do PHP necessárias para Yii
RUN docker-php-ext-install pdo pdo_mysql mbstring xml intl zip gd


# Instalar composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/backend

# Instalar guzzle pelo composer durante o build
# OBS: Só vai instalar realmente se o composer.json existir no contexto
RUN composer require guzzlehttp/guzzle:^7.4 symfony/dom-crawler:^6.4 --no-interaction


# Tornar acessível para webserver
EXPOSE 9000

CMD ["php-fpm"]
