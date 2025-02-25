# Use an official PHP image with Apache
FROM php:8.2-apache

# Enable required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy files to the container
COPY . /var/www/html/

# Set permissions
RUN chmod -R 755 /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
