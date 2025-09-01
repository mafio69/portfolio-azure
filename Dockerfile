# Etap 1: Budowanie Vue.js
FROM node:18 AS frontend-build

WORKDIR /app/frontend

# Kopiuj package.json i package-lock.json
COPY ./frontend/package*.json ./
RUN npm install

# Kopiuj kod aplikacji do kontenera
COPY ./frontend/ .
RUN ls -la /app/frontend
RUN ls -la /app/frontend/dist

# Buduj produkcyjny frontend
RUN npm run build

# Etap 2: PHP + nginx
FROM php:8.3-fpm

# Instalacja nginx, zależności PHP itp.
RUN apt-get update && apt-get install -y nginx git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www
# Instalacja Composera - dodaj przed RUN composer install

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Kopiowanie backendu PHP (np. Slim)
COPY ./backend/composer.json backend/composer.lock ./
COPY ./backend/ /var/www/backend/
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-plugins --working-dir=/var/www/backend

# Kopiowanie konfiguracji nginx i start.sh
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/start.sh /start.sh

RUN chown -R www-data:www-data /var/www && chmod +x /start.sh
# Kopiowanie zbudowanego frontendu Vue.js
COPY --from=frontend-build /app/frontend/dist /var/www/html/public
EXPOSE 80
RUN apt-get update && apt-get install -y supervisor

# Skopiuj plik konfiguracyjny supervisora do obrazu

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Zmień CMD na uruchomienie supervisora
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
