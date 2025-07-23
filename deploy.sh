#!/bin/bash

# Nama image dan container
IMAGE_NAME=laravel-franken
CONTAINER_NAME=laravel-app

# Port container di host
HOST_PORT=8080

echo "🔧 1. Building Docker image..."
docker build -t $IMAGE_NAME .

echo "🚀 2. Menjalankan container..."
docker run -d --name $CONTAINER_NAME -p $HOST_PORT:80 $IMAGE_NAME

echo "📁 3. Menyalin .env.production ke container..."
docker cp .env.production $CONTAINER_NAME:/var/www/html/.env

echo "🔑 4. Generate Key..."
docker exec -it $CONTAINER_NAME php artisan key:generate

echo "🔃 5. Menjalankan migrate dan seed..."
docker exec -it $CONTAINER_NAME php artisan migrate --force
docker exec -it $CONTAINER_NAME php artisan db:seed --force

echo "🧹 6. Membersihkan dan mencache konfigurasi..."
docker exec -it $CONTAINER_NAME php artisan config:clear
docker exec -it $CONTAINER_NAME php artisan config:cache
docker exec -it $CONTAINER_NAME php artisan route:cache
docker exec -it $CONTAINER_NAME php artisan view:cache

echo "✅ Deploy selesai. Laravel berjalan di http://localhost:$HOST_PORT"
