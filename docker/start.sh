#!/bin/sh

set -e

echo "Starting SameCRM Application..."

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php -r "try { \$pdo = new PDO('mysql:host=mysql;port=3306', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'MySQL is ready!'; exit(0); } catch (PDOException \$e) { exit(1); }" 2>/dev/null; do
    echo "MySQL is unavailable - sleeping"
    sleep 1
done

# Change to application directory
cd /var/www/html/application

# Check if .env exists, if not copy from example
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "Copying .env.example to .env..."
        cp .env.example .env
    else
        echo "Creating .env file..."
        cat > .env <<EOF
APP_NAME=SameCRM
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=${DB_DATABASE:-samecrm}
DB_USERNAME=${DB_USERNAME:-samecrm_user}
DB_PASSWORD=${DB_PASSWORD:-samecrm_password}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
EOF
    fi
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/application/storage
chmod -R 775 /var/www/html/storage

# Run migrations (optional - uncomment if you want auto-migration)
# echo "Running migrations..."
# php artisan migrate --force

# Clear and cache config
echo "Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Start supervisor
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
