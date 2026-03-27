FROM php:8.4.1-fpm-alpine AS base

RUN apk add --no-cache \
    git \
    unzip \
    curl \
    libzip-dev \
    oniguruma-dev \
    icu-dev

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

WORKDIR /var/www

FROM base AS builder

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY . .

RUN composer dump-autoload --optimize

FROM base

ENV ENABLE_CACHE=true

COPY --from=builder /var/www /var/www

COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

RUN mkdir -p /tmp/php-temp \
    && chmod 1777 /tmp/php-temp \
    && echo "sys_temp_dir=/tmp/php-temp" > /usr/local/etc/php/conf.d/temp.ini \
    && echo "upload_tmp_dir=/tmp/php-temp" >> /usr/local/etc/php/conf.d/temp.ini

EXPOSE 9000

ENTRYPOINT ["sh", "/entrypoint.sh"]

ARG UID=1000
ARG GID=1000
RUN deluser www-data \
    && addgroup -g $GID www-data \
    && adduser -D -G www-data -u $UID www-data

USER www-data

CMD ["php-fpm"]
