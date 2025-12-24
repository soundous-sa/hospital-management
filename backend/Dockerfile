FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring

WORKDIR /var/www

COPY . .

RUN php artisan key:generate

CMD php artisan serve --host=0.0.0.0 --port=8000
