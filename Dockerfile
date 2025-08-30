FROM php:8.3-fpm

RUN apt-get update && apt-get install -y nginx git unzip libzip-dev \
    && docker-php-ext-install zip
# Skopiuj pliki aplikacji DO /var/www/html (tylko backend — bez cluttera buildów frontu)
COPY ./backend/ /var/www/html/
# Skopiuj kompozytor oraz zależności (zakładając composer.json w backend/)
WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --classmap-authoritative --working-dir=/var/www/html
# Upewnij się, że użytkownik nginx/php ma dostęp do katalogu aplikacji
RUN chown -R www-data:www-data /var/www

COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 8080

# CMD ["nginx", "-g", "daemon off;"]
# CMD service nginx start && php-fpm
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]
