# Saída: 8 — Refatoração: BrokerInterface, RabbitMQ, BrokerProvider

Pedido do usuário: "execute as etapas restantes". Prompt em `docs/prompts-desenvolvimento/8-refactor-broker.md`. Passo a passo em `docs/steps-human__/passo-a-passo.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | `ms-queues`, `transaction-app/nginx/db`, `notification-app/nginx` |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

`POST /transaction` + consumidor imprimiu `Printing: {"transaction":53,"name":"Cicero Ondricka","email":"ibecker@example.net","value":53}`.

## Pendências e decisões

- Host/porta/credenciais do broker vêm do `.env` (`RABBITMQ_*`), com os mesmos valores como padrão.
- O `NotificationCommand` não foi refatorado (desafio final da aula).
