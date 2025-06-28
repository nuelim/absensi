# Stage 1: Builder - Menginstall dependensi dan build aset
FROM php:8.2-fpm as builder

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy dependency files
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Copy application files
COPY . .

# Run composer dump-autoload
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm install && npm run build

# Stage 2: Production Image - Gambar akhir yang lebih kecil
FROM php:8.2-fpm

# Install Nginx dan supervisor
RUN apt-get update && apt-get install -y nginx supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql gd

# Set working directory
WORKDIR /var/www/html

# Copy application files from builder stage
COPY --from=builder /app .

# Copy Nginx and Supervisor configuration
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]