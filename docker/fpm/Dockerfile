FROM php:8.4-fpm-alpine

# Install SUPERVISOR and dependencies
RUN apk add --update rabbitmq-c-dev icu-dev nodejs npm libpq postgresql-dev

# Install PHP extensions
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
	&& docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql intl \
    && pecl install amqp redis apcu \
    && docker-php-ext-enable amqp pdo_pgsql redis apcu \
    && apk del pcre-dev ${PHPIZE_DEPS}

RUN curl -s https://getcomposer.org/installer | php
RUN mv /var/www/html/composer.phar /usr/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /srv/app
