# Gunakan image resmi frankenphp dengan PHP 8.2 atau versi terbaru
FROM dunglas/frankenphp:latest

# Set working directory di container
WORKDIR /app

# Salin seluruh source code Laravel ke dalam container
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN cp .env.example .env
# Install dependensi menggunakan Composer
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Berikan permission untuk Laravel storage & bootstrap
RUN chmod -R 775 storage bootstrap/cache

# Jalankan Laravel optimization commands
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expose port 80 untuk HTTP
EXPOSE 80

# Gunakan FrankenPHP untuk menjalankan Laravel
CMD ["frankenphp", "--document-root", "public", "--bootstrap", "bootstrap/app.php"]
