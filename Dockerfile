FROM composer:latest AS composer
FROM php:8.1-cli

RUN apt-get update && apt-get install -y unzip
RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json .
RUN composer install

COPY . .

EXPOSE 8080/tcp

CMD ["php", "-S", "0.0.0.0:8080", "router.php"]