FROM php:fpm-alpine

COPY ./app/ /var/www/html

WORKDIR /var/www/html

RUN apk update && apk add mysql-client zip libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip
