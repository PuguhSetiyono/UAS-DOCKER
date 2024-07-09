FROM php:8.1-fpm


WORKDIR /var/www/html


RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug


RUN apt-get clean && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . /var/www/html


COPY --chown=www-data:www-data . /var/www/html


EXPOSE 9000
CMD ["php-fpm"]
