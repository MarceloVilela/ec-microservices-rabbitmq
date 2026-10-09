# Saída: 3 — ms-transaction: migrations, seed, rota e TransactionService

Pedido do usuário: "execute etapa #3". Prompt em `docs/prompts-desenvolvimento/3-transaction-migrations-seed.md`.

## Resultado

| Item | Resultado |
|---|---|
| Containers no ar | os 6 de sempre (`transaction-db` healthy) |
| Migrations | `2021_09_20_000001_create_users_table`, `2021_09_20_000002_create_transactions_table` |
| Commit | não feito |

## Teste real

- `php artisan migrate:fresh --seed`: `users` = 10, `transactions` = 50.
- `POST localhost:8004/transaction` com `{"payer":5,"payee":10,"value":53}` → `{"message":"Transaction created successfully."}`; a linha 51 apareceu (`5, 10, 53.00`).
- Body inválido (`payer` 99, `value` -1) → erros de validação (`The selected payer is invalid.`, `The value must be greater than 0.`).
- Depois do teste apaguei a linha 51 e reajustei o `auto_increment`, para a transação 51 vir da etapa 4. Estado final: 50 transações.

## Decisões e pendências

- Colunas de `transactions`: `payer`, `payee` (FK para `users`), `value` decimal(10,2). Os nomes são inferência (o print da aula não mostra o cabeçalho).
- Camadas como na aula: Controller → `TransactionService` → `TransactionRepository`, ligados em `AppServiceProvider` (registrado no `bootstrap/app.php`, junto com `withFacades` e `withEloquent`).
- A factory sorteia payer de 1 a 5 e payee de 6 a 10, como os dados da aula. Os nomes dos usuários vêm do Faker, não são idênticos aos dos prints.
- `.env` e `.env.example` do `ms-transaction` apontam para `transaction-db` / `pay_db` / `root`.
- `UserRepository` (`findById`) fica para a etapa 6, quando o `payee` for buscado para a notificação.
- Pendente: etapa 4 (`client.http`).
