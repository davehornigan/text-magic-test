FROM php:8.1-fpm

RUN apt update && apt install -y curl wget git libicu-dev libmcrypt-dev libonig-dev libpq-dev \
    && docker-php-ext-install -j$(nproc) iconv mbstring pgsql pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/public

CMD ["php-fpm"]