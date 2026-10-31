# Saída: 5 — ms-notification: POST /notification/send e log no Loggly

Pedido do usuário: "execute as etapas restantes". Prompt em `docs/prompts-desenvolvimento/5-notification-loggly.md`. Passo a passo em `docs/steps-human__/passo-a-passo.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | `ms-queues`, `transaction-app/nginx/db`, `notification-app/nginx` |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

`POST /notification/send` → `{"data":"Notification sent successfully."}`; validação devolve erros por campo. Com token, o `LogglyHandler` envia; o endpoint do Loggly respondeu `{"response" : "ok"}` a um envio direto. **Não confirmado:** o painel de `lumemrabbit`/`lumemrabbit.loggly.com` seguiu em "No log data" (ver pendências).

## Pendências e decisões

- Sem `LOGGLY_TOKEN`, grava em `storage/logs/notification.log`.
- Pendente: confirmar o evento no Loggly (conta/subdomínio do token).
