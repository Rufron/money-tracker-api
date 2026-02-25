#!/bin/bash

# Exit on error
set -e

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