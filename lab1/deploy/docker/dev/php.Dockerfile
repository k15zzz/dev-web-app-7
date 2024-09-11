FROM php:8.1-fpm

WORKDIR /var/www/

# Обновляем систему и устанавливаем необходимые библиотеки
RUN apt-get update && apt-get install -y \
        file \
        libcurl4-gnutls-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libgmp-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libmhash-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        re2c \
        zlib1g-dev \
        libpq-dev \
        && \
        ln -s /usr/lib/x86_64-linux-gnu/libsybdb.a /usr/lib/ && \
        ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/

# Извлекаем исходники PHP для установки расширений
RUN docker-php-source extract

# Настраиваем и устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ && \
    docker-php-ext-configure gmp

# Устанавливаем расширения PHP, включая pdo_mysql, zip и pdo_pgsql для работы с PostgreSQL
RUN docker-php-ext-install -j$(nproc) gd gmp pdo_mysql pdo_pgsql zip

# Удаляем исходники PHP после установки
RUN docker-php-source delete

# Добавляем пользовательский php.ini
ADD deploy/config/dev/php/php.ini /usr/local/etc/php/conf.d/40-custom.ini
