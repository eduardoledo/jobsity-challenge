FROM php:7.4-fpm

RUN apt update
RUN apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev
#RUN docker-php-ext-configure mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install apcu
RUN docker-php-ext-enable apcu
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

WORKDIR /var/www/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

