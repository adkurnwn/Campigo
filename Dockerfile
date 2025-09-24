FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libsodium-dev \
    libicu-dev \
    nodejs \
    npm \
    supervisor \
    netcat-traditional \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring pcntl bcmath gd zip exif opcache sodium intl

# Install Composer
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock /var/www/
RUN composer install --no-dev --no-scripts --no-autoloader

# Copy package.json for Node dependencies
COPY package.json package-lock.json* /var/www/

# Copy application code
COPY . /var/www

# Create production environment files
COPY .env.example /var/www/.env

# Complete composer installation and generate autoload
RUN composer dump-autoload --optimize


RUN npm install
RUN npm run build
RUN npm prune --production

#Laravel application key
RUN php artisan key:generate --force \
    && php artisan config:cache 

# Create a backup of public files for volume initialization
RUN cp -r /var/www/public /var/www/public_backup

# Set proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && chmod -R 755 /var/www/public

# Create supervisor configuration for Laravel processes
RUN mkdir -p /var/log/supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create entrypoint script to handle volume initialization
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]