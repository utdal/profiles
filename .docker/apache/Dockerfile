FROM public.ecr.aws/docker/library/httpd:2.4-trixie AS base_build

ARG CONTAINER_ENV
ENV CONTAINER_ENV=${CONTAINER_ENV:-production}

ARG DOCUMENT_ROOT
ENV DOCUMENT_ROOT=${DOCUMENT_ROOT:-/var/www/html}

RUN mkdir -p /var/www/html
COPY .docker/.bashrc /root/.bashrc

# Local Dev
FROM base_build AS local_build
COPY .docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf

# Staging
FROM base_build AS staging_build
COPY .docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf
WORKDIR ${DOCUMENT_ROOT}
COPY ./public public

# Production
FROM base_build AS production_build
COPY .docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf
WORKDIR ${DOCUMENT_ROOT}
COPY ./public public