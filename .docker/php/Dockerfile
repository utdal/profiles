FROM php:8.3-fpm AS base_build

# Arguments & Environment Variables
ARG CONTAINER_ENV
ENV CONTAINER_ENV=${CONTAINER_ENV:-production}

ARG APP_ENV
ENV APP_ENV=${APP_ENV:-production}

ARG DOCUMENT_ROOT
ENV DOCUMENT_ROOT=${DOCUMENT_ROOT:-/var/www/html}

ARG DB_HOST
ENV DB_HOST=${DB_HOST:-db}

ARG DB_PORT
ENV DB_PORT=${DB_PORT:-3306}

ARG DB_DATABASE
ENV DB_DATABASE=${DB_DATABASE:-utdlink}

ARG DB_USERNAME
ENV DB_USERNAME=${DB_USERNAME:-root}

ARG DB_PASSWORD
ENV DB_PASSWORD=${DB_PASSWORD:-root}

# Common
RUN apt-get update
COPY .docker/.bashrc /root/.bashrc

# PHP
COPY .docker/php/php.ini $PHP_INI_DIR/conf.d/zzz-01-custom.ini
RUN apt-get install -y libzip-dev libldap2-dev
RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure ldap --with-libdir=lib/$(arch)-linux-gnu/
RUN docker-php-ext-install opcache pdo pdo_mysql mysqli zip exif pcntl ldap

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Sendmail (for Mailpit)
FROM base_build AS mail_build
RUN apt-get update \
    && apt-get install -y git \
    && cd ~ \
    && curl -o go.tar.gz https://dl.google.com/go/go1.23.8.linux-arm64.tar.gz \
    && tar -C /usr/local -xzf go.tar.gz \
    && export PATH=$PATH:/usr/local/go/bin \
    && git clone https://github.com/axllent/mailpit.git \
    && cd mailpit/sendmail \
    && go build -ldflags "-s -w" \
    && cp ~/mailpit/sendmail/sendmail /usr/local/bin/sendmail

# App files
FROM base_build AS app_files
WORKDIR $DOCUMENT_ROOT
COPY . .
RUN cp ".env.${APP_ENV}" .env
RUN chown -R www-data:www-data storage

# Vendor files
FROM app_files AS vendor_files
WORKDIR $DOCUMENT_ROOT
RUN php -d allow_url_fopen=On /usr/local/bin/composer install

# Local Dev
# Local Dev
FROM base_build AS local_build
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Imagick
RUN apt-get update && apt-get install -y libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY .docker/php/xdebug.ini $PHP_INI_DIR/conf.d/zzz-xdebug.ini

COPY --from=mail_build /usr/local/bin/sendmail /usr/local/bin/sendmail
COPY .docker/php/email.ini $PHP_INI_DIR/conf.d/zzz-email.ini

# Staging
FROM base_build AS staging_build
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
WORKDIR $DOCUMENT_ROOT
COPY --from=app_files $DOCUMENT_ROOT .
COPY --from=vendor_files $DOCUMENT_ROOT/vendor vendor
RUN mkdir /var/www/html/shared

# Prod
FROM base_build AS production_build
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
WORKDIR $DOCUMENT_ROOT
COPY --from=app_files $DOCUMENT_ROOT .
COPY --from=vendor_files $DOCUMENT_ROOT/vendor vendor
RUN mkdir /var/www/html/shared