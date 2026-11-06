
#!/bin/sh
set -e

echo "Starting LaunchBridge..."

php artisan migrate --force

echo "Starting Laravel server..."

exec php artisan serve \
    --host=0.0.0.0 \
    --port="${PORT:-10000}"
