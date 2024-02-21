FROM php:8.1.0-fpm-alpine

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

RUN pecl install xdebug-3.1.5 && docker-php-ext-enable xdebug

COPY ./docker/php/zzz-xdebug.ini /usr/local/etc/php/conf.d/zzz-xdebug.ini

ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

COPY ./ /var/www
WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD php-fpm

EXPOSE 9000