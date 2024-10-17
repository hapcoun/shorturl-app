FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    supervisor \
    redis-tools \
    libzip-dev \
    zip \
    unzip \
    cron \
    && docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

COPY . /var/www

WORKDIR /var/www

RUN composer install --prefer-dist --no-interaction --optimize-autoloader

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["supervisord", "-n"]