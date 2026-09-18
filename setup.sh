#!/bin/bash
set -e

# ============================================================
#  Webblioteca — setup totalmente automático
#
#  Faz, sem intervenção manual:
#    1. Verifica se o comando docker existe
#    2. Se o motor do Docker estiver desligado, inicia o
#       Docker Desktop automaticamente e aguarda ele ficar pronto
#    3. Cria o .env
#    4. Recria os containers do projeto em estado limpo
#    5. Ajusta permissões, instala dependências (PHP e Node),
#       compila assets (Vite), gera a chave e popula o banco
#    6. Valida e exibe o endereço de acesso
# ============================================================

echo "Configurando a Webblioteca..."
echo ""

# ------------------------------------------------------------
# 1. O comando docker precisa existir no PATH
# ------------------------------------------------------------
if ! command -v docker >/dev/null 2>&1; then
    echo "❌ ERRO: comando 'docker' não encontrado."
    echo ""
    echo "O Docker não está disponível neste ambiente. Verifique, nesta ordem:"
    echo ""
    echo "  1. Docker Desktop está instalado no Windows? Se não, instale em:"
    echo "     https://www.docker.com/products/docker-desktop/"
    echo "     (depois feche e reabra o terminal para o comando 'docker' entrar no PATH)"
    echo ""
    echo "  2. A integração com o WSL está ativada no Docker Desktop?"
    echo "     Settings → Resources → WSL Integration → marque a distro Ubuntu → Apply & Restart"
    echo ""
    echo "  3. A virtualização está ativada na BIOS? (Intel VT-x / AMD SVM)"
    echo ""
    echo "  4. Os recursos 'Plataforma de Máquina Virtual' e"
    echo "     'Subsistema do Windows para Linux' estão ativos?"
    echo "     Painel de Controle → Programas → Ativar ou desativar recursos do Windows"
    echo ""
    exit 1
fi

# ------------------------------------------------------------
# 2. Garantir que o motor do Docker esteja em execução
# ------------------------------------------------------------
if ! docker info >/dev/null 2>&1; then
    echo "O motor do Docker não está em execução. Iniciando o Docker Desktop automaticamente..."

    # Caminho oficial: o próprio CLI do Docker inicia o Docker Desktop
    if ! docker desktop start >/dev/null 2>&1; then
        echo "Falha ao iniciar via 'docker desktop start'. Tentando abrir o executável diretamente..."
        # Fallback: localiza o executável no Windows (Program Files ou LocalAppData) e o abre a partir do WSL
        powershell.exe -NoProfile -Command "Get-ChildItem 'C:\Program Files\Docker\Docker','$env:LOCALAPPDATA\Programs\DockerDesktop' -Recurse -Filter 'Docker Desktop.exe' -ErrorAction SilentlyContinue | Select-Object -First 1 | ForEach-Object { Start-Process $_.FullName -WorkingDirectory $_.DirectoryName }" >/dev/null 2>&1 || true
    fi

    echo "Aguardando o motor do Docker ficar pronto (na primeira vez pode levar alguns minutos)..."
    attempts=0
    until docker info >/dev/null 2>&1; do
        attempts=$((attempts + 1))
        if [ "$attempts" -ge 90 ]; then   # ~4min30s de espera
            echo ""
            echo "❌ ERRO: o Docker não ficou pronto a tempo."
            echo ""
            echo "Isso costuma indicar um ambiente do Windows não configurado. Verifique:"
            echo "  • Virtualização ativada na BIOS (Intel VT-x / AMD SVM)"
            echo "  • Recursos do Windows 'Plataforma de Máquina Virtual' e"
            echo "    'Subsistema do Windows para Linux' ativos em:"
            echo "    Painel de Controle → Programas → Ativar ou desativar recursos do Windows"
            echo "  • Docker Desktop instalado e com integração WSL habilitada"
            echo "    (Settings → Resources → WSL Integration)"
            echo ""
            echo "Abra o Docker Desktop manualmente; se ele exibir um erro, corrija e rode o setup de novo."
            exit 1
        fi
        sleep 3
    done
    echo "✔ Docker ativo."
fi

# ------------------------------------------------------------
# 3. Criar o arquivo .env, se necessário
# ------------------------------------------------------------
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✔ Arquivo .env criado a partir do .env.example"
fi

# ------------------------------------------------------------
# 3.5. Instalar dependências ANTES de subir os containers
#      (o compose.yaml do Sail precisa de vendor/laravel/sail
#       para conseguir construir a imagem — em um clone novo essa
#       pasta ainda não existe, então usamos um container
#       descartável só para este passo)
# ------------------------------------------------------------
if [ ! -d "vendor/laravel/sail" ]; then
    echo "Pasta vendor/ ausente (clone novo) — instalando dependências via container temporário..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/app" \
        -w /app \
        composer:2 \
        composer install --ignore-platform-reqs --no-interaction
    echo "✔ Dependências instaladas."
fi

# ------------------------------------------------------------
# 4. Containers: recriar o projeto em estado limpo
# ------------------------------------------------------------
echo ""
echo "Subindo os containers Docker..."

# O build da imagem precisa saber qual usuário/grupo do host usar.
# Normalmente isso é feito pelo próprio ./vendor/bin/sail — como
# chamamos o Docker Compose diretamente, exportamos manualmente.
export WWWUSER=$(id -u)
export WWWGROUP=$(id -g)

docker compose down --remove-orphans >/dev/null 2>&1 || true
docker compose up -d

# Descobre o container principal (independente do nome da pasta)
APP_CONTAINER="$(docker compose ps -q laravel.test)"

echo "Aguardando o container 'laravel.test' iniciar..."
for _ in $(seq 1 30); do
    if [ "$(docker inspect -f '{{.State.Running}}' "$APP_CONTAINER" 2>/dev/null || echo false)" = "true" ]; then
        break
    fi
    sleep 2
done

if [ "$(docker inspect -f '{{.State.Running}}' "$APP_CONTAINER" 2>/dev/null || echo false)" != "true" ]; then
    echo ""
    echo "❌ ERRO: o container 'laravel.test' não ficou em execução. Últimos logs:"
    docker logs "$APP_CONTAINER" --tail 50 2>&1 || true
    exit 1
fi

# ------------------------------------------------------------
# 5. Permissões, chave da aplicação e banco de dados
# ------------------------------------------------------------

echo "Ajustando permissões de storage e bootstrap/cache..."
docker compose exec -u root laravel.test chmod -R 777 storage bootstrap/cache

echo "Gerando chave da aplicação..."
docker compose exec laravel.test php artisan key:generate

echo "Aguardando o banco de dados ficar pronto e populando..."
until docker compose exec laravel.test php artisan migrate --seed; do
    echo "Banco ainda não está pronto, tentando novamente em 3s..."
    sleep 3
done

# ------------------------------------------------------------
# 5.5. Instalação de pacotes Node e compilação de assets (Vite)
# ------------------------------------------------------------
echo ""
echo "Instalando dependências de frontend (npm)..."
docker compose exec -u root laravel.test npm install --no-audit --no-fund

echo "Ajustando permissões dos binários e módulos..."
docker compose exec -u root laravel.test chmod -R 777 node_modules public
docker compose exec -u root laravel.test chmod -R +x node_modules/.bin

echo "Compilando assets com Vite..."
docker compose exec laravel.test npm run build

# ------------------------------------------------------------
# 6. Validação final
# ------------------------------------------------------------
if command -v curl >/dev/null 2>&1; then
    echo "Verificando a aplicação em http://localhost ..."
    url_code="000"
    tries=0
    until [ "$url_code" != "000" ]; do
        url_code=$(curl -s -o /dev/null -w '%{http_code}' --max-time 5 http://localhost 2>/dev/null || true)
        tries=$((tries + 1))
        if [ "$tries" -ge 20 ]; then
            break
        fi
        sleep 3
    done
    echo "Resposta HTTP de http://localhost: ${url_code}"
fi

echo ""
echo "======================================================"
echo "✅ Tudo pronto! Acesse http://localhost"
echo ""
echo "Login de administrador: admin@admin.com / admin@webblioteca"
echo "======================================================"