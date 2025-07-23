FROM dunglas/frankenphp

WORKDIR /app

COPY . /app

RUN apt-get update && apt-get install -y zip libzip-dev
RUN docker-php-ext-install pcntl zip
RUN docker-php-ext-enable zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \ 
    apt-get install -y nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN cp .env.example .env

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

RUN php artisan octane:install --server=frankenphp

RUN chmod -R 775 storage bootstrap/cache

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN npm install && npm run build 
EXPOSE 80

CMD ["php", "artisan", "octane:frankenphp" , "--port=80", "--host=0.0.0.0"]
