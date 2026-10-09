#!/bin/sh
# Para os 3 serviços (mantém volumes). Use ./stop.sh -v para apagar também os dados.
cd "$(dirname "$0")" || exit 1
for svc in ms-notification ms-transaction ms-queues; do
  docker compose -f "$svc/docker-compose.yml" down "$@"
done
