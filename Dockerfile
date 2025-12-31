FROM php:8.2-cli AS builder
WORKDIR /app
COPY src/ /app
FROM php:8.2-apache
RUN docker-php-ext-install mysqli \
    && apt-get update \
    && apt-get install -y curl

RUN a2enmod rewrite

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html
