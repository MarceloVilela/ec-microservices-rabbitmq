# Saída: 4 — client.http

Pedido do usuário: "execute etapa #4". Prompt em `docs/prompts-desenvolvimento/4-client-http.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | os 6 de sempre |
| Migrations | nenhuma |
| Commit | não feito |

## Teste real

- Requisição do `client.http` (executada com `curl`, mesmos headers e body) → `{"message":"Transaction created successfully."}`.
- `select * from transactions where id=51` → `51, 5, 10, 53.00`; total = 51.

## Decisões e pendências

- `client.http` na raiz, formato da extensão REST Client do VS Code (também aceito pelo JetBrains).
- Com isso o boilerplate (etapas 1 a 4) está completo. Pendente: etapas 5 a 9 (conteúdo da aula).
- Rodar o `client.http` de novo cria as transações 52, 53...; para voltar a 50, `./run.sh ms-transaction php artisan migrate:fresh --seed`.

## Correções do code review do boilerplate

- `run.sh`: cria `.env` a partir do `.env.example`, roda `composer install` se faltar `vendor/` e roda `migrate --seed` no fim. Clone limpo testado (sem `vendor` nem `.env`, `./stop.sh -v` antes): 6 containers no ar, Lumen em 8004/8005, 10 usuários e 50 transações.
- `DatabaseSeeder` só popula se não houver usuários; `db:seed` repetido mantém 50 transações.
- Portas publicadas só em `127.0.0.1` (8004, 8005, 3308, 5672, 15672), confirmado com `ss -ltn`.
- `UID`/`GID` renomeados para `HOST_UID`/`HOST_GID` nos compose e no `run.sh`.
- README atualizado com o primeiro uso e as portas.
- Não corrigidos (baixa prioridade): `payer` = `payee`, status 200 vs 201, dotfiles no nginx, `restart:`/healthchecks, `php ^8.0.2` do `composer.json`.
