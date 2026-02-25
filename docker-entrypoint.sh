#!/bin/bash

# Exit on error
set -e

# Fix permissions on every start (critical for Render)
echo "🔧 Setting correct permissions..."
chmod 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database 2>/dev/null || true
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chown -R www-data:www-data /var/www/html/bootstrap/cache 2>/dev/null || true

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground