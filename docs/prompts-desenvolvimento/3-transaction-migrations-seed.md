# 3 — ms-transaction: migrations, seed, rota e TransactionService

Etapa 3 do `specs.md` (Fase 3), boilerplate. Requisitos R1 e R6.

Pedido: criar as migrations de `users` e `transactions`, o seed (10 usuários e 50 transações), a rota `POST /transaction` e o `TransactionService`.

Escopo: a rota só persiste a transação; sem publicar na fila (etapa 6).

Aceite: `select count(*) from transactions` = 50.
