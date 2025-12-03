# Basé sur l'image PHP FPM standard
FROM php:8.4-fpm

# Installation des dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    zlib1g-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache zip intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Définition du répertoire de travail
WORKDIR /var/www/html
