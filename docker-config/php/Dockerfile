FROM php:7.2-fpm

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN usermod -u 1000 www-data