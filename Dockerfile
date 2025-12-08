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
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation et activation de Xdebug
RUN docker-php-ext-enable xdebug

# Création d'un fichier ini dédié pour Xdebug CLI
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=no" >> /usr/local/etc/php/conf.d/xdebug.ini

# Définition du répertoire de travail
WORKDIR /var/www/html

COPY ./BackEnd/ /var/www/html/

# Installer Composer si ce n'est pas déjà fait
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Copier le script d'entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Définir l’entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Commande par défaut (php-fpm)
CMD ["php-fpm"]