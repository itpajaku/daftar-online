#!/bin/bash

# Nama image dan container
IMAGE_NAME=daftar-online-larafran
CONTAINER_NAME=daftar-online-app

# Port container di host
HOST_PORT=8002

echo "🔧 1. Building Docker image..."
docker build -t $IMAGE_NAME .

echo "🛑 2. Menghentikan container lama..."
docker stop $CONTAINER_NAME || true
docker rm $CONTAINER_NAME || true

echo "🚀 3. Menjalankan container..."
# Pastikan ada file .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Pastikan file database.sqlite ada sebelum docker run
# (Jika tidak, Docker akan otomatis membuat direktori bernama 'database.sqlite' yang akan menyebabkan error)
if [ ! -f database.sqlite ]; then
    touch database.sqlite
    chmod 666 database.sqlite
fi

docker run -d \
  -v $(pwd)/storage:/app/storage \
  -v $(pwd)/database.sqlite:/app/database/database.sqlite \
  -v $(pwd)/.env:/app/.env \
  --name $CONTAINER_NAME \
  -p $HOST_PORT:80 \
  $IMAGE_NAME

echo "✅ Deploy selesai. Laravel berjalan di http://localhost:$HOST_PORT"
