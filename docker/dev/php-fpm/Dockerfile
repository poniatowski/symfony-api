FROM php:7.4-fpm

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && touch /var/log/xdebug.log \
    && chmod 0777 /var/log/xdebug.log \
    && docker-php-ext-install pgsql pdo_pgsql \

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
