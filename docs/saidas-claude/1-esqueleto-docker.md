# Saída — 1-esqueleto-docker

## Resultado

| Item | Resultado |
|---|---|
| Containers | `ms-queues`, `transaction-app`, `transaction-nginx`, `transaction-db` (healthy), `notification-app`, `notification-nginx` |
| Migrations | nenhuma |
| `localhost:8004` | `Lumen (10.0.4) (Laravel Components ^10.0)` |
| `localhost:8005` | `Lumen (10.0.4) (Laravel Components ^10.0)` |
| Painel RabbitMQ `localhost:15672` | HTTP 200 |
| PHP no container | 8.4.26 (`pdo_mysql`, `bcmath`, `sockets`) |
| Rede | `ec-network` externa; `ms-queues`, `transaction-db` e `notification-app` resolvem de dentro do `transaction-app` |
| Commit | não feito |

## Decisões

- Versões atuais no lugar das da aula (PHP 8.0.10, Lumen 8.2.4, MySQL 5.7, RabbitMQ 3.9.4); registrado no `specs.md`.
- Lumen 10 sobe em PHP 8.4 sem avisos de depreciação no boot (conferir de novo nas etapas 3 e 7).
- Dockerfile Alpine precisa de `linux-headers` para compilar `sockets`.
- `transaction-db` publicado na 3308, porque a 3306 está ocupada pelo `mysql-laravel`.
- `Dockerfile` e `nginx/default.conf` ficam em `.docker/` de cada serviço, como no original da aula.
- O `run.sh` aceita `./run.sh <servico> <comando>` para rodar composer/artisan no container.

## Pendências

- Etapa 2: conferir login `admin`/`admin` no painel.
