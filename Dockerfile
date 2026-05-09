# Use the official PHP image with Apache
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev # Required for PostgreSQL

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Increase Composer memory limit
ENV COMPOSER_MEMORY_LIMIT=-1

# Install Composer dependencies with verbose output
RUN composer install --no-dev --optimize-autoloader -vvv

# Generate application key (if not already set in .env)
# This is usually handled by Render's env vars, but good to have as fallback
RUN php artisan key:generate --force

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
