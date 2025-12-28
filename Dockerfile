FROM php:8.1-apache
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd mysqli
RUN a2enmod rewrite
COPY src/ /var/www/html/
COPY database.sql /docker-entrypoint-initdb.d/
RUN mkdir -p /var/www/html/uploads && chmod 755 /var/www/html/uploads
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]