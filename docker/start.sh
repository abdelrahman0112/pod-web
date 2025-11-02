#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
