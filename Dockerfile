# Multi-stage Dockerfile for Laravel CRM Application
# Stage 1: Build stage for Node.js assets (Optional - can be skipped if not needed)
# FROM node:18-alpine AS node-builder
# 
# WORKDIR /app
# 
# # Copy package files
# COPY application/package*.json ./
# RUN npm install
# 
# # Copy webpack config and assets
# COPY application/webpack.mix.js ./
# COPY application/resources ./resources
# 
# # Build assets
# RUN npm run production

# Stage 2: PHP Composer dependencies
FROM php:8.2-fpm-alpine AS composer-stage

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files
COPY application/composer*.json ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Stage 3: Final production image
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache \
    && apk del --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev

# Configure PHP
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_input_time = 300" >> /usr/local/etc/php/conf.d/custom.ini

# Configure OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . /var/www/html/

# Copy vendor from composer stage
COPY --from=composer-stage --chown=www-data:www-data /app/vendor /var/www/html/application/vendor

# Create necessary directories
RUN mkdir -p /var/www/html/application/storage/framework/sessions \
    && mkdir -p /var/www/html/application/storage/framework/views \
    && mkdir -p /var/www/html/application/storage/framework/cache \
    && mkdir -p /var/www/html/application/storage/logs \
    && mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/avatars \
    && mkdir -p /var/www/html/storage/logos \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/application/storage \
    && chmod -R 775 /var/www/html/storage

# Copy Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
