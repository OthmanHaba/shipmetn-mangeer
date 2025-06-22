FROM php:8.2-fpm

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
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

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

# Switch to www-data user
USER www-data

# Create startup script
COPY --chown=www-data:www-data <<EOF /var/www/html/start.sh
#!/bin/bash
set -e

echo "Starting Laravel application setup..."

# Generate app key if not exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate --force

# Wait for database to be ready
echo "Waiting for database to be ready..."
until pg_isready -h db -p 5432 -U postgres; do
    echo "Database is unavailable - sleeping"
    sleep 2
done

echo "Database is ready!"

# Run Laravel setup commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force

echo "Laravel setup completed!"

# Start the application
exec php artisan serve --host=0.0.0.0 --port=8000
EOF

RUN chmod +x /var/www/html/start.sh

# Expose port 8000
EXPOSE 8000

# Start the application
CMD ["/var/www/html/start.sh"]
