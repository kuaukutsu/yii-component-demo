FROM ghcr.io/kuaukutsu/php:8.2-fpm as app_fpm_build

FROM app_fpm_build AS app_setup

# Arguments
ARG UID=10001
ARG WORKDIR="/src"

# Configure
COPY conf/www.conf.default /usr/local/etc/php-fpm.d/www.conf.default

RUN echo "# adduser #" \
    && adduser -u $UID -G www-data -s /bin/sh -D developer www-data

RUN echo "# create structure #" \
    && mkdir -p $WORKDIR \
    && chown -R $UID:www-data $WORKDIR

RUN echo "# mongodb #" \
    && install-php-extensions mongodb

COPY --from=composer:latest --link /usr/bin/composer /usr/bin/composer

FROM app_setup AS app_devel

# Configure
COPY conf/php.ini /usr/local/etc/php/php.ini
COPY conf/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN echo "# xdebug #" \
    && install-php-extensions xdebug

USER $UID
WORKDIR $WORKDIR

EXPOSE 9000
