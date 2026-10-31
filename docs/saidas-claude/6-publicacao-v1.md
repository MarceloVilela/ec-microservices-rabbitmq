# Saída: 6 — Publicação v1 na fila

Pedido do usuário: "execute as etapas restantes". Prompt em `docs/prompts-desenvolvimento/6-publicacao-v1.md`. Passo a passo em `docs/steps-human__/passo-a-passo.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | `ms-queues`, `transaction-app/nginx/db`, `notification-app/nginx` |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

`POST /transaction` → 200; `notification_queue` classic, durable, Ready 1.

## Pendências e decisões

- RabbitMQ 4 recusa a fila não durável da aula (`transient_nonexcl_queues`): usado `durable = true`.
- Host `ms-queues` em vez de IP de gateway.
- A transação é gravada antes da publicação, sem rollback se a fila falhar.
