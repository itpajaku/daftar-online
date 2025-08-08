#!/bin/bash

# Nama image dan container
IMAGE_NAME=daftar-online-larafran
CONTAINER_NAME=daftar-online

# Port container di host
HOST_PORT=8080

echo "🔧 1. Building Docker image..."
docker build -t $IMAGE_NAME .

echo "🚀 2. Menjalankan container..."
docker run -d -v $(pwd)/storage:/app/storage \ --name $CONTAINER_NAME -p $HOST_PORT:80 $IMAGE_NAME

echo "📁 3. Menyalin .env.production ke container..."
docker cp .env.example $CONTAINER_NAME:/app/.env

echo "⏳ 4. Menyiapkan Database..."
docker exec -it $CONTAINER_NAME touch database/database.sqlite
sleep 5  # Beri waktu container startup

echo "🔃 5. Menjalankan migrate dan seed..."
docker exec -it $CONTAINER_NAME php artisan migrate
sleep 10  # Beri waktu container startup
docker exec -it $CONTAINER_NAME php artisan db:seed
sleep 10  # Beri waktu container startup

echo "🔑 6. Generating key..."
docker exec -t $CONTAINER_NAME php artisan key:generate

echo "🧹 7. Membersihkan dan mencache konfigurasi..."
docker exec -it $CONTAINER_NAME php artisan cache:clear
docker exec -it $CONTAINER_NAME php artisan config:clear
docker exec -it $CONTAINER_NAME php artisan config:cache

echo "✅ Deploy selesai. Laravel berjalan di http://localhost:$HOST_PORT"
