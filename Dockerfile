# Utiliser l’image officielle PHP avec Apache
FROM php:8.4-apache

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev pkg-config libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer extensions PHP nécessaires pour Laravel
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le code de l’application
COPY . .

# Créer fichier SQLite et ajuster permissions
RUN touch database/database.sqlite && \
    chown -R www-data:www-data database && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Augmenter la limite mémoire Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader -vvv

# Installer Node.js pour assets frontend
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install && npm run build

# Configurer Apache pour Laravel
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Exposer port 80
EXPOSE 80

# Commande de démarrage : migrations + seed + Apache
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground
