FROM php:8.3-apache AS builder

# Update system packages and install dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip gnupg \
    libpng-dev libjpeg-dev libwebp-dev libonig-dev libzip-dev \
    libpq-dev default-mysql-client \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql mbstring exif bcmath gd zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (for React + Inertia build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN npm install && npm run build

# Create Laravel storage folders and set permissions
RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Fix Apache document root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
