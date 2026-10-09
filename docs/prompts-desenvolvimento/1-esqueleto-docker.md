# 1 — Esqueleto Docker dos 3 serviços

Etapa 1 do `specs.md` (Fase 3), boilerplate.

Pedido: criar `ms-queues`, `ms-transaction` e `ms-notification`, cada um com seu `docker-compose`, além de `run.sh` e `stop.sh`. Stack: PHP 8.4, Lumen 10, MySQL 8.4, RabbitMQ 4, nginx 1.27-alpine. O host não tem php nem composer.

Escopo: só infraestrutura e Lumen vazio. Sem rotas, migrations nem fila (etapas 2 a 4 e 5 a 9).

Aceite: `./run.sh` sobe os serviços; `localhost:8004` e `localhost:8005` respondem o Lumen; painel do RabbitMQ em `localhost:15672`.
