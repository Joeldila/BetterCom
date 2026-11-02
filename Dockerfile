
FROM php:8.3-apache

# Install PHP extensions required by BetterCom
RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

# Set application directory
WORKDIR /var/www/html

# Copy BetterCom project files
COPY . /var/www/html/

# Create uploads directory and set permissions
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/uploads

# Railway communicates with Apache on port 80
EXPOSE 80

CMD ["apache2-foreground"]
