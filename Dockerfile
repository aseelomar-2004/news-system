FROM php:8.2-cli AS builder
WORKDIR /app
COPY src/ /app
FROM php:8.2-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY --from=builder /app /var/www/html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
