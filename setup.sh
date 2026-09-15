#!/bin/bash
set -e

echo "Configurando a Webblioteca..."

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env criado a partir do .env.example"
fi
/b
echo "Subindo os containers Docker..."
docker compose up -d

echo "Instalando dependências..."
docker compose exec laravel.test composer install

echo "Gerando chave da aplicação..."
docker compose exec laravel.test php artisan key:generate

echo "Aguardando o banco de dados ficar pronto..."
until docker compose exec laravel.test php artisan migrate --seed; do
    echo "Banco ainda não está pronto, tentando novamente em 3s..."
    sleep 3
done

echo ""
echo "Tudo pronto! Acesse http://localhost"
echo "Login de administrador: admin@admin.com / admin@webblioteca"