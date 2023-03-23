FROM php:8.1-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update -y && apt-get install -y libpng-dev libonig-dev libzip-dev libwebp-dev git
RUN docker-php-ext-configure gd --with-webp
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip exif

RUN mkdir -p /var/www/html/public

WORKDIR /var/www/html

RUN echo "alias pest='vendor/bin/pest'" >> ~/.bashrc
