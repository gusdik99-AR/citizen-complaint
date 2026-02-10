# Multi-stage Dockerfile for Laravel + Vue/Inertia Application
# Supports both development (with hot reload) and production builds

# ============================================
# Base Stage: PHP 8.2 with Node.js and Nginx
# ============================================
FROM php:8.2-fpm AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Remove default Nginx config and create symlink
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# ============================================
# Dependencies Stage: Install PHP dependencies
# ============================================
FROM base AS dependencies

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev dependencies for production)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application files
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# ============================================
# Frontend Build Stage: Build Vite assets
# ============================================
FROM base AS frontend-builder

# Copy package files
COPY package*.json ./

# Install Node.js dependencies
RUN npm ci

# Copy application files
COPY . .

# Build frontend assets for production
RUN npm run build

# ============================================
# Development Stage: For local development
# ============================================
FROM base AS development

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy application files first
COPY . .

# Install ALL dependencies (including dev)
RUN composer install --prefer-dist

# Install Node.js dependencies
RUN npm ci

# Set permissions for storage, bootstrap cache, and node_modules
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/node_modules

# Copy supervisor configuration for development
COPY docker/supervisor/supervisord-dev.conf /etc/supervisor/conf.d/supervisord.conf

# Expose ports
EXPOSE 8000 5173

# Start supervisor (runs PHP-FPM, Nginx, and Vite dev server)
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]

# ============================================
# Production Stage: Optimized for deployment
# ============================================
FROM base AS production

# Copy PHP dependencies from dependencies stage
COPY --from=dependencies /var/www/html/vendor ./vendor

# Copy built frontend assets from frontend-builder stage
COPY --from=frontend-builder /var/www/html/public/build ./public/build

# Copy application files
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy supervisor configuration for production
COPY docker/supervisor/supervisord-prod.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8000/up || exit 1

# Start supervisor (runs PHP-FPM and Nginx)
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
