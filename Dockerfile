FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libonig-dev libicu-dev curl git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd intl \
    && a2enmod rewrite

RUN echo "opcache.jit=off" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.jit_buffer_size=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
