FROM php:8.2-fpm-alpine

# Install system dependencies, Nginx, Python3 (for yt-dlp), ffmpeg, and build libraries
RUN apk add --no-cache \
    nginx \
    curl \
    python3 \
    ffmpeg \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash

# Install PHP extensions required by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install yt-dlp binary (grab the latest release)
RUN curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp \
    && chmod a+rx /usr/local/bin/yt-dlp

# Configure Nginx
COPY nginx.conf /etc/nginx/http.d/default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Set directory permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run composer install to install PHP dependencies in production mode
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Make sure entrypoint script is executable
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Expose port 80 for Nginx
EXPOSE 80

# Run entrypoint script
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
