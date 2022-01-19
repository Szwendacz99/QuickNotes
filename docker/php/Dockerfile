FROM php:8.0.12-fpm-alpine3.13

RUN apk add --no-cache --virtual .build-deps postgresql-dev && \
    docker-php-ext-install -j "$(nproc)" pgsql pdo_pgsql && \
    apk del .build-deps && \
    apk add --no-cache --virtual .run-deps postgresql-libs
