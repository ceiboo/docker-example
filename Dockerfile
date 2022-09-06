FROM php:8.1-fpm

# Argumentos definidos en docker-compose.yml
ARG user
ARG uid

# Instalamos dependencias en el contenedor php
RUN apt-get update && apt-get install -y \
    git \
    curl \
    nano \
    mc \
    autoconf automake make gcc g++

RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    zip \
    unzip \
    libzip-dev

# Limpiamos cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalamos extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && pecl install xdebug \
    && docker-php-ext-enable \
            xdebug

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip

# Instalamos en el contenedor php la ultima imagen disponible de composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos la configuración de php que tenemos en nuestra carpeta .docker
COPY ./.docker/php/ /usr/local/etc/php/

# Creamos un usurio dentro de nuestro contenedor
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Establecemos nuestro directorio de trabajo
WORKDIR /var/www

USER $user
