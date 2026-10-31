# Saída: 7 — NotificationCommand consome a fila

Pedido do usuário: "execute as etapas restantes". Prompt em `docs/prompts-desenvolvimento/7-notification-command.md`. Passo a passo em `docs/steps-human__/passo-a-passo.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | `ms-queues`, `transaction-app/nginx/db`, `notification-app/nginx` |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

`php artisan list` mostra `notification`; execução imprimiu `Printing: Hello, World!`; Ready foi a 0.

## Pendências e decisões

- Registro com `\` inicial no `Kernel`.
- `timeout` não encerra o PHP dentro do container; usar `pkill -f "artisan notification"`.
- Erro meu no meio do caminho: um `sed` quebrou o `Kernel` (syntax error), corrigido.
