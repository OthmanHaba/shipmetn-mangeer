# Docker Deployment Guide

This guide will help you deploy the Shipment Accounting application using Docker Compose.

## Prerequisites

- Docker and Docker Compose installed on your system
- At least 2GB of available RAM
- At least 5GB of available disk space

## Quick Start

1. **Clone the repository** (if not already done):
   ```bash
   git clone <your-repo-url>
   cd shipment.accounting
   ```

2. **Create environment file**:
   ```bash
   cp .env.example .env
   ```

3. **Update the .env file** with Docker-specific settings:
   ```bash
   # Database settings for Docker
   DB_CONNECTION=pgsql
   DB_HOST=db
   DB_PORT=5432
   DB_DATABASE=shipment_accounting
   DB_USERNAME=postgres
   DB_PASSWORD=password
   
   # Redis settings
   REDIS_HOST=redis
   REDIS_PORT=6379
   
   # Cache and session settings
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   ```

4. **Generate application key**:
   ```bash
   # You can generate a key locally first
   php artisan key:generate
   ```

5. **Build and start the containers**:
   ```bash
   docker-compose up -d --build
   ```

6. **Wait for the application to be ready**:
   The application will automatically:
   - Install Composer dependencies
   - Run database migrations
   - Seed the database
   - Start the Laravel development server

7. **Access the application**:
   - Main application: http://localhost:8000
   - Database: localhost:5432 (for external connections)
   - Redis: localhost:6379 (for external connections)

## Services

### App Service (Laravel)
- **Container**: `shipment_accounting_app`
- **Port**: 8000
- **Features**: 
  - PHP 8.2 with all required extensions
  - Composer for dependency management
  - Automatic database setup
  - File caching and optimization

### Database Service (PostgreSQL)
- **Container**: `shipment_accounting_db`
- **Port**: 5432
- **Database**: `shipment_accounting`
- **Username**: `postgres`
- **Password**: `password`
- **Data**: Persisted in Docker volume `postgres_data`

### Redis Service
- **Container**: `shipment_accounting_redis`
- **Port**: 6379
- **Usage**: Caching and session storage

## Useful Commands

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
```

### Execute commands in the app container
```bash
# Laravel Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Composer commands
docker-compose exec app composer install
docker-compose exec app composer update
```

### Stop and start services
```bash
# Stop all services
docker-compose down

# Start services
docker-compose up -d

# Restart specific service
docker-compose restart app
```

### Database operations
```bash
# Access PostgreSQL shell
docker-compose exec db psql -U postgres -d shipment_accounting

# Create database backup
docker-compose exec db pg_dump -U postgres shipment_accounting > backup.sql

# Restore database
docker-compose exec -T db psql -U postgres shipment_accounting < backup.sql
```

## Customization

### Environment Variables
You can override any environment variable in the `docker-compose.yml` file or create a `.env.docker` file:

```yaml
# In docker-compose.yml under app service
environment:
  - APP_ENV=production
  - APP_DEBUG=false
  - DB_PASSWORD=your_secure_password
```

### Volumes
- Application files: `./:/var/www/html`
- Storage: `./storage:/var/www/html/storage`
- Cache: `./bootstrap/cache:/var/www/html/bootstrap/cache`
- Database: `postgres_data:/var/lib/postgresql/data`

### Ports
You can change the exposed ports in `docker-compose.yml`:
```yaml
ports:
  - "8080:8000"  # Change 8080 to your preferred port
```

## Production Considerations

For production deployment, consider:

1. **Security**:
   - Change default passwords
   - Use environment variables for sensitive data
   - Enable HTTPS with a reverse proxy (nginx, Traefik)

2. **Performance**:
   - Use production-optimized images
   - Enable OPcache in PHP
   - Use dedicated Redis for caching

3. **Monitoring**:
   - Add health checks
   - Set up log aggregation
   - Monitor resource usage

4. **Backup**:
   - Schedule regular database backups
   - Backup application files and storage

## Troubleshooting

### Common Issues

1. **Permission Issues**:
   ```bash
   # Fix storage permissions
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   docker-compose exec app chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Issues**:
   - Ensure the database service is running
   - Check the database credentials in `.env`
   - Wait for the database to be fully initialized

3. **Memory Issues**:
   - Increase Docker's memory limit
   - Optimize Composer with `--no-dev` flag

4. **Port Conflicts**:
   - Change the port mapping in `docker-compose.yml`
   - Kill processes using the same ports

### Getting Help

If you encounter issues:
1. Check the container logs: `docker-compose logs -f app`
2. Verify all services are running: `docker-compose ps`
3. Check the application logs in `storage/logs/`
4. Ensure all required environment variables are set 
