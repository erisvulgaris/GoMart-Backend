FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    libonig-dev \
    default-mysql-client \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd zip intl mysqli pdo pdo_mysql mbstring

# Enable Apache ModRewrite
RUN a2enmod rewrite

# Configure Apache Document Root to point to CodeIgniter's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Run dos2unix on entrypoint and set permissions
RUN dos2unix /var/www/html/entrypoint.sh && chmod +x /var/www/html/entrypoint.sh

# Set permissions for writable and uploads directories
RUN mkdir -p /var/www/html/public/uploads && chown -R www-data:www-data /var/www/html/writable /var/www/html/public/uploads


EXPOSE 80

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
