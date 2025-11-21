# Dockerfile pour Drupal 11 en production
# Image PHP-FPM Alpine légère avec extensions PHP uniquement
# Le code Drupal est monté depuis l'hôte, pas inclus dans l'image

FROM php:8.3-fpm-alpine

LABEL maintainer="Esokami <dev@esokami.fr>"
LABEL description="PHP-FPM 8.3 pour Drupal 11 (production)"

# Variables d'environnement pour le build
ENV PHPIZE_DEPS \
    autoconf \
    dpkg-dev dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c

# Installation des dépendances système et extensions PHP requises par Drupal 11
RUN apk add --no-cache \
    # Outils de base
    bash \
    git \
    unzip \
    # Extensions PHP - Bibliothèques
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    # Client MySQL
    mysql-client \
    # FastCGI pour healthcheck
    fcgi \
    # Mise à jour de sécurité
    && apk upgrade --no-cache \
    # Configuration de GD avec support JPEG, PNG, WebP, FreeType
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    # Installation des extensions PHP
    && docker-php-ext-install -j$(nproc) \
        gd \
        opcache \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        intl \
        mbstring \
        bcmath \
        exif \
    # Installation APCu et Redis via PECL
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install apcu redis \
    && docker-php-ext-enable apcu redis \
    && apk del .build-deps \
    # Nettoyage
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Création de l'utilisateur www-data avec UID/GID spécifiques
RUN set -eux; \
    deluser --remove-home www-data 2>/dev/null || true; \
    delgroup www-data 2>/dev/null || true; \
    addgroup -g 1000 www-data; \
    adduser -u 1000 -D -S -G www-data www-data

# Copie des configurations PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/drupal.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Création des répertoires nécessaires
RUN mkdir -p \
    /var/www/html \
    /var/log/php-fpm \
    && chown -R www-data:www-data \
        /var/www/html \
        /var/log/php-fpm \
    && chmod -R 775 \
        /var/log/php-fpm

# Script de healthcheck
COPY docker/healthcheck.sh /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/healthcheck

# Répertoire de travail
WORKDIR /var/www/html

# Utilisateur non-root
USER www-data

# Exposition du port PHP-FPM
EXPOSE 9000

# Healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD /usr/local/bin/healthcheck

# Démarrage PHP-FPM
CMD ["php-fpm"]
