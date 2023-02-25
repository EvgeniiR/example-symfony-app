FROM php:8.1-fpm

ENV XDG_CACHE_HOME=/tmp/.cache
ENV COMPOSER_CACHE_DIR=${XDG_CACHE_HOME}/composer

COPY --from=composer:2.5 /usr/bin/composer /usr/local/bin/composer
RUN  --mount=type=bind,from=mlocati/php-extension-installer:2.0,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
      install-php-extensions zip xdebug-stable mbstring pdo_pgsql

RUN adduser app
RUN mkdir -p /app && chown -R app /app && mkdir -p /tmp && chown -R app /tmp

USER app
WORKDIR /app

COPY www.conf /etc/php/8.1/fpm/pool.d/www.conf

EXPOSE 9000
ENTRYPOINT ["php-fpm", "-F"]
