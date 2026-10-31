# Saída: 9 — Teste de resiliência

Pedido do usuário: "execute as etapas restantes". Prompt em `docs/prompts-desenvolvimento/9-resiliencia.md`. Passo a passo em `docs/steps-human__/passo-a-passo.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | `ms-queues`, `transaction-app/nginx/db`, `notification-app/nginx` |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

Sem consumidor: 3 `POST` → `ready 3, consumers 0`. Consumidor de volta: imprimiu as transações 57, 58 e 59 (FIFO); depois `ready 0`.

## Pendências e decisões

- As estatísticas da API do painel atrasam; esperar ~15 s antes de ler.
- Mensagens não persistentes: um restart do RabbitMQ as perde.
