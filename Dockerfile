# Étape 1 : Build (Composer & Artisan)
FROM php:8.2-fpm-alpine AS builder

# Installer dépendances système
RUN apk add --no-cache \
    git unzip libpng-dev libjpeg-turbo-dev freetype-dev icu-dev oniguruma-dev bash mariadb-client

# Installer extensions PHP nécessaires à Laravel
RUN docker-php-ext-install pdo pdo_mysql intl mbstring gd exif pcntl bcmath opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier fichiers du projet
COPY . .

# Installer les dépendances PHP sans dev et optimiser
RUN composer install --no-dev --optimize-autoloader && \
    composer dump-autoload --optimize

# Générer caches Laravel
RUN php artisan config:clear && \
    php artisan view:clear && \
    php artisan route:clear

# Étape 2 : Runtime (léger)
FROM php:8.2-fpm-alpine AS runtime

# Installer extensions PHP (mêmes que ci-dessus)
RUN apk add --no-cache \
    libpng libjpeg-turbo freetype icu oniguruma bash mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl mbstring gd bcmath opcache

WORKDIR /var/www

# Copier uniquement le nécessaire depuis builder
COPY --from=builder /var/www /var/www

# Fixer permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
