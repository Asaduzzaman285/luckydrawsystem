FROM php:8.2-fpm

# system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# php extensions required by laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# redis extension
RUN pecl install redis && docker-php-ext-enable redis

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www