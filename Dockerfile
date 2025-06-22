FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    postgresql-client \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Create startup script
RUN echo '#!/bin/bash' > /var/www/html/start.sh && \
    echo 'set -e' >> /var/www/html/start.sh && \
    echo 'echo "Starting Laravel application setup..."' >> /var/www/html/start.sh && \
    echo 'if [ ! -f .env ]; then cp .env.example .env; fi' >> /var/www/html/start.sh && \
    echo 'php artisan key:generate --force' >> /var/www/html/start.sh && \
    echo 'echo "Waiting for database to be ready..."' >> /var/www/html/start.sh && \
    echo 'until pg_isready -h db -p 5432 -U postgres; do' >> /var/www/html/start.sh && \
    echo '    echo "Database is unavailable - sleeping"' >> /var/www/html/start.sh && \
    echo '    sleep 2' >> /var/www/html/start.sh && \
    echo 'done' >> /var/www/html/start.sh && \
    echo 'echo "Database is ready!"' >> /var/www/html/start.sh && \
    echo 'php artisan config:cache' >> /var/www/html/start.sh && \
    echo 'php artisan route:cache' >> /var/www/html/start.sh && \
    echo 'php artisan view:cache' >> /var/www/html/start.sh && \
    echo 'php artisan migrate --force' >> /var/www/html/start.sh && \
    echo 'php artisan db:seed --force' >> /var/www/html/start.sh && \
    echo 'echo "Laravel setup completed!"' >> /var/www/html/start.sh && \
    echo 'exec php artisan serve --host=0.0.0.0 --port=8000' >> /var/www/html/start.sh


# Switch to www-data user
USER www-data

# Expose port 8000
EXPOSE 8000

# Start the application
CMD ["/var/www/html/start.sh"]
