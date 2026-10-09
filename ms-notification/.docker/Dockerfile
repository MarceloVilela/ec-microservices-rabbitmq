FROM php:8.4-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS linux-headers \
 && docker-php-ext-install pdo_mysql bcmath sockets \
 && apk del $PHPIZE_DEPS linux-headers

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
