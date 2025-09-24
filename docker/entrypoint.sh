#!/bin/bash
set -e

#Initialize public files volume
if [ -d "/var/www/public" ]; then
    PUBLIC_VOLUME_FILES=$(find /var/www/public -mindepth 1 -maxdepth 1 2>/dev/null | wc -l)
    
    if [ "$PUBLIC_VOLUME_FILES" -eq 0 ]; then
        echo "Initializing public files volume..."
        
        # Copy built public files from backup to volume
        if [ -d "/var/www/public_backup" ]; then
            cp -r /var/www/public_backup/* /var/www/public/ 2>/dev/null || true
            echo "Public files copied from backup"
        fi
    fi
fi

#Initialize storage
mkdir -p /var/www/storage/app/public /var/www/storage/logs /var/www/storage/framework/cache /var/www/storage/framework/sessions /var/www/storage/framework/views

# Initialize storage symlink if it doesn't exist
if [ ! -L "/var/www/public/storage" ] && [ -d "/var/www/storage/app/public" ]; then
    echo "Creating storage symlink..."
    ln -sf /var/www/storage/app/public /var/www/public/storage
fi

#ownership for volumes
chown -R www-data:www-data /var/www/storage /var/www/public 2>/dev/null || true
chmod -R 775 /var/www/storage 2>/dev/null || true  
chmod -R 755 /var/www/public 2>/dev/null || true

#update or add environment variable in .env file
update_env_var() {
    local key="$1"
    local value="$2"
    local env_file="/var/www/.env"
    
    # Escape special characters in the value for sed
    local escaped_value=$(echo "$value" | sed 's/[[\.*^$()+?{|]/\\&/g')
    
    if grep -q "^${key}=" "$env_file"; then
        # Update existing variable - only update if value actually changed
        current_value=$(grep "^${key}=" "$env_file" | cut -d'=' -f2-)
        if [ "$current_value" != "$escaped_value" ]; then
            sed -i "s|^${key}=.*|${key}=${escaped_value}|" "$env_file"
            echo "  ✓ Updated ${key}"
            return 0
        fi
        return 1  # No change needed
    else
        # Add new variable
        echo "${key}=${value}" >> "$env_file"
        echo "  ✓ Added ${key}"
        return 0
    fi
}

ENV_VARS_TO_SYNC=(
    "APP_NAME"
    "APP_ENV" 
    "APP_DEBUG"
    "APP_URL"
    "DB_CONNECTION"
    "DB_HOST"
    "DB_PORT"
    "DB_DATABASE"
    "DB_USERNAME"
    "DB_PASSWORD"
    "CACHE_DRIVER"
    "SESSION_DRIVER"
    "QUEUE_CONNECTION"
    "LOG_CHANNEL"
    "LOG_LEVEL"
    "MAIL_MAILER"
    "MAIL_HOST"
    "MAIL_PORT"
)

CHANGES_MADE=0
for var in "${ENV_VARS_TO_SYNC[@]}"; do
    if [ ! -z "${!var}" ]; then
        if update_env_var "$var" "${!var}"; then
            CHANGES_MADE=$((CHANGES_MADE + 1))
        fi
    fi
done

if [ $CHANGES_MADE -eq 0 ]; then
    echo "  No environment variables needed updating"
else
    echo "  Synchronized $CHANGES_MADE environment variables"
fi

echo "Environment variables sync completed"

#show database configuration
echo "Verifying database configuration:"
echo "  DB_CONNECTION: $(grep "^DB_CONNECTION=" /var/www/.env | cut -d'=' -f2- || echo 'NOT SET')"
echo "  DB_HOST: $(grep "^DB_HOST=" /var/www/.env | cut -d'=' -f2- || echo 'NOT SET')"
echo "  DB_DATABASE: $(grep "^DB_DATABASE=" /var/www/.env | cut -d'=' -f2- || echo 'NOT SET')"
echo "Testing network connectivity to database host..."
DB_HOST_FROM_ENV=$(grep "^DB_HOST=" /var/www/.env | cut -d'=' -f2- || echo 'db')
DB_PORT_FROM_ENV=$(grep "^DB_PORT=" /var/www/.env | cut -d'=' -f2- || echo '3306')

# Wait for the database service to be available
RETRY_COUNT=0
MAX_RETRIES=30
echo "Waiting for MySQL service to start on $DB_HOST_FROM_ENV:$DB_PORT_FROM_ENV..."

while ! nc -z "$DB_HOST_FROM_ENV" "$DB_PORT_FROM_ENV" 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Database service not reachable after $MAX_RETRIES attempts"
        echo "Checking if database container is running..."
        echo "Current network connectivity test failed for $DB_HOST_FROM_ENV:$DB_PORT_FROM_ENV"
        exit 1
    fi
    echo "Database service not ready, waiting 2 seconds... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done

#test Laravel database connection
RETRY_COUNT=0
MAX_RETRIES=15
until php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo 'Database connected successfully';
} catch (Exception \$e) {
    echo 'Connection failed: ' . \$e->getMessage();
    exit(1);
}
" 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Failed to connect to database after $MAX_RETRIES attempts"
        echo "Current DB configuration:"
        grep "^DB_" /var/www/.env || echo "No DB_ variables found in .env"
        echo ""
        echo "Testing direct MySQL connection..."
        mysql -h"$DB_HOST_FROM_ENV" -P"$DB_PORT_FROM_ENV" -u"$(grep "^DB_USERNAME=" /var/www/.env | cut -d'=' -f2-)" -p"$(grep "^DB_PASSWORD=" /var/www/.env | cut -d'=' -f2-)" -e "SELECT 1;" 2>&1 || echo "Direct MySQL connection also failed"
        exit 1
    fi
    echo "Laravel database connection not ready, waiting 3 seconds... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 3
done

php artisan config:clear

echo "Container initialization complete"

#Execute the main command
exec "$@"