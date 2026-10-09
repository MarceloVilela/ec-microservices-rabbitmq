#!/bin/sh
# Sobe os 3 serviços (rede compartilhada ec-network, depois queues, transaction e notification).
# No primeiro uso também cria o .env, instala o vendor e migra/popula o banco.
# Uso: ./run.sh            sobe tudo
#      ./run.sh <servico> <comando...>   roda comando no container app (ex.: ./run.sh ms-transaction php artisan about)
cd "$(dirname "$0")" || exit 1
HOST_UID="$(id -u)"; HOST_GID="$(id -g)"
export HOST_UID HOST_GID

# $1 = serviço; o resto é o comando, executado no container app (sem subir o db)
in_app() {
  svc="$1"; shift
  docker compose -f "$svc/docker-compose.yml" run --rm --no-deps -e HOME=/tmp -e COMPOSER_HOME=/tmp/composer app "$@"
}

if [ $# -gt 0 ]; then
  svc="$1"; shift
  exec docker compose -f "$svc/docker-compose.yml" run --rm --no-deps -e HOME=/tmp -e COMPOSER_HOME=/tmp/composer app "$@"
fi

docker network inspect ec-network >/dev/null 2>&1 || docker network create ec-network
docker compose -f ms-queues/docker-compose.yml up -d --build

for svc in ms-transaction ms-notification; do
  [ -f "$svc/src/.env" ] || cp "$svc/src/.env.example" "$svc/src/.env"
  [ -f "$svc/src/vendor/autoload.php" ] || in_app "$svc" composer install --no-interaction
  docker compose -f "$svc/docker-compose.yml" up -d --build
done

# o app só sobe com o db saudável; o seed só roda se o banco estiver vazio
docker compose -f ms-transaction/docker-compose.yml run --rm -e HOME=/tmp app php artisan migrate --seed --force
