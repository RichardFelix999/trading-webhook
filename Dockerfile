# Use official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install zip mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files (including composer.json)
COPY . /var/www/html

# Install PHP dependencies safely
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
