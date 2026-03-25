#!/bin/sh

set -e

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Esperando banco..."
while ! nc -z "$DB_HOST" "${DB_PORT:-3306}"; do
  sleep 1
done

php artisan config:clear
php artisan route:clear
php artisan cache:clear

if [ "$ENABLE_CACHE" = "true" ]; then
  php artisan config:cache
  php artisan route:cache
fi

exec "$@"
