# JIG-SPOTS API image (PHP 8.3 FPM). Used by both the `api` and `worker` services.
# PINNED, not the floating 8.3 tag — see docker/api/Dockerfile.prod for the full
# reasoning. Short version: 8.3.36 carries the PHP2Shell fixes (CVE-2026-109618 /
# CVE-2026-109619, both unauthenticated RCE), and a floating tag lets a cached local
# base layer rebuild a vulnerable image without anyone noticing.
FROM php:8.3.36-fpm-alpine

# System deps + PHP extensions via the helper installer.
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
        pdo_pgsql \
        pgsql \
        sockets \
        intl \
        zip \
        gd \
        bcmath \
        pcntl \
        opcache \
    && apk add --no-cache postgresql-client bash procps su-exec

# Composer (copied from the official image).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Production-friendly opcache settings.
COPY docker/api/php.ini /usr/local/etc/php/conf.d/zz-spmis.ini

WORKDIR /var/www/html

COPY docker/api/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Build-time ownership; the entrypoint re-applies it to mounted volumes at runtime
# and then drops privileges to www-data via su-exec.
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
