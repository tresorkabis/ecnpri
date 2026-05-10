# Use the official PHP image with Apache
FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 # Required for SQLite

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Create SQLite database file and set permissions for storage
RUN touch database/database.sqlite && \
    chmod 664 database/database.sqlite && \
    chown www-data:www-data database/database.sqlite && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Increase Composer memory limit
ENV COMPOSER_MEMORY_LIMIT=-1

# Install Composer dependencies with verbose output
RUN composer install --no-dev --optimize-autoloader -vvv

# Run npm install and build for frontend assets
# Ensure Node.js is available if you have frontend assets
# For this, we'll add Node.js to the image, or use a multi-stage build
# For simplicity, let's add Node.js directly for now.
# A more robust solution would be a multi-stage build.
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs
RUN npm install && npm run build

# Configure Apache for Laravel
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
