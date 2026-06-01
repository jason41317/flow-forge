FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN php artisan package:discover --ansi

# Permissions (Laravel needs this)
RUN chmod -R 775 storage bootstrap/cache

# Expose port Render expects
ENV PORT=10000
EXPOSE 10000

# Start Laravel using built-in server (Render-friendly)
CMD php artisan serve --host=0.0.0.0 --port=${PORT}