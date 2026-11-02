
FROM php:8.3-apache

# Install MySQLi
RUN docker-php-ext-install mysqli

# Remove every enabled Apache MPM module
RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && a2enmod mpm_prefork rewrite \
    && apache2ctl -M \
    && test "$(apache2ctl -M 2>/dev/null | grep -c 'mpm_')" -eq 1

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Create uploads directory
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/uploads

EXPOSE 80

CMD ["apache2-foreground"]
