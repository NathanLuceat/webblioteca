#!/bin/bash
set -e

echo "Configurando a Webblioteca..."

# Pré-checagem: o comando docker precisa existir no PATH
if ! command -v docker >/dev/null 2>&1; then
    echo ""
    echo "❌ ERRO: comando 'docker' não encontrado."
    echo ""
    echo "O Docker não está disponível neste ambiente. Verifique, nesta ordem:"
    echo ""
    echo "  1. Docker Desktop está instalado no Windows? Se não, instale em:"
    echo "     https://www.docker.com/products/docker-desktop/"
    echo ""
    echo "  2. Integração com o WSL está ativada no Docker Desktop?"
    echo "     Settings → Resources → WSL Integration → marque a distro Ubuntu → Apply & Restart"
    echo ""
    echo "  3. Virtualização ativada na BIOS? (Intel VT-x / AMD SVM)"
    echo ""
    echo "  4. Recursos do Windows ativados? 'Plataforma de Máquina Virtual' e"
    echo "     'Subsystem do Windows para Linux' — em Ativar ou desativar recursos do Windows"
    echo ""
    echo "Depois de resolver, confirme com:  docker --version"
    exit 1
fi

# Pré-checagem: o daemon do Docker precisa estar em execução
if ! docker info >/dev/null 2>&1; then
    echo ""
    echo "❌ ERRO: Docker está instalado, mas o daemon não está em execução."
    echo "Abra o Docker Desktop e aguarde o status indicar que está rodando antes de executar novamente."
    exit 1
fi

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env criado a partir do .env.example"
fi

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