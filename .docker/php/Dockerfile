FROM public.ecr.aws/docker/library/php:8.3-fpm-trixie AS base_build

# Arguments & Environment Variables
ARG APP_ENV=production

ENV DOCUMENT_ROOT=/var/www/html

# Common
COPY .docker/.bashrc /root/.bashrc

# PHP
COPY .docker/php/php.ini $PHP_INI_DIR/conf.d/zzz-01-custom.ini
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests libzip-dev libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/$(arch)-linux-gnu/ \
    && docker-php-ext-install opcache pdo pdo_mysql mysqli zip exif pcntl ldap \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=public.ecr.aws/composer/composer:latest /usr/bin/composer /usr/local/bin/composer

# PIE: PHP Installer for Extensions
COPY --from=ghcr.io/php/pie:bin /pie /usr/local/bin/pie

# Extensions: Imagick
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
    # PIE build tools
    libtool \
    git \
    # ImageMagick library
    libmagickwand-dev \
    # for SVG support
    libmagickcore-7.q16hdri-10-extra \
    # for HEIC support
    libheif-dev \
    && pie install imagick/imagick --with-imagick=/usr \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

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
    && cp ~/mailpit/sendmail/sendmail /usr/local/bin/sendmail \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# App files
FROM base_build AS app_files
ARG APP_ENV
WORKDIR $DOCUMENT_ROOT
COPY . .
RUN cp ".env.${APP_ENV}" .env
RUN chown -R www-data:www-data storage

# Vendor files
FROM app_files AS vendor_files
WORKDIR $DOCUMENT_ROOT
RUN php -d allow_url_fopen=On /usr/local/bin/composer install

# Local Dev
FROM base_build AS local_build
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Xdebug
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests libtool git \
    && pie install xdebug/xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
COPY .docker/php/xdebug.ini $PHP_INI_DIR/conf.d/zzz-xdebug.ini

COPY --from=mail_build /usr/local/bin/sendmail /usr/local/bin/sendmail
COPY .docker/php/email.ini $PHP_INI_DIR/conf.d/zzz-email.ini

# Entrypoint
COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

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