# Stage 1: Build assets dengan Node.js
FROM node:22 AS node-builder

WORKDIR /app

# Copy file yang dibutuhkan untuk build
COPY package*.json ./
RUN npm install

COPY . . 
RUN npm run build


# Stage 2: FrankenPHP + PHP dependencies
FROM dunglas/frankenphp:php8.3 AS app

WORKDIR /app

COPY . /app

ENV TZ=Asia/Jakarta

RUN apt-get update && apt-get install -y zip libzip-dev \
    && docker-php-ext-install pcntl zip bcmath pdo pdo_mysql mysqli \
    && docker-php-ext-enable zip pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
RUN php artisan octane:install --server=frankenphp
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache
# RUN php artisan config:cache && \
#     php artisan route:cache && \
#     php artisan view:cache

# Copy hasil build assets dari node-builder
COPY --from=node-builder /app/public/build /app/public/build

# Copy dan set permission untuk script entrypoint
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

ENTRYPOINT ["start.sh"]
CMD ["php", "artisan", "octane:frankenphp" , "--port=80", "--host=0.0.0.0"]
