# Gunakan image FrankenPHP sebagai base image
FROM dunglas/frankenphp

# Set working directory di dalam container
WORKDIR /app

# Salin file Laravel ke container
COPY . /app

# Install dependencies Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Set permission untuk Laravel (storage dan bootstrap/cache)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Salin file konfigurasi FrankenPHP jika kamu punya
# COPY frankenphp.yaml /etc/frankenphp.yaml

# Jalankan perintah artisan untuk generate key (hanya jika belum)
# Pastikan .env sudah tersedia
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan optimize

# Expose port 80 untuk HTTP
EXPOSE 80

# Perintah default untuk menjalankan FrankenPHP
CMD ["frankenphp", "--document-root=/app/public", "--worker=/app/public/index.php"]
