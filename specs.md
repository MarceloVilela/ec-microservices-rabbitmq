# Plano SDD: recriar `ec-microservices-rabbitmq`

Plano de *spec driven design* para recriar o repositório da aula "PHP | microsserviços com RabbitMQ" (Rocketseat), que não existe mais. Só planejamento: nada foi implementado.

Fontes (em `notas-github/aulas/rocket/php/microservicos-rabbit-mq/`): `1.1-microservicos-rabbit-mq.md`, `repo-not-found__.md`, `wiki/wiki.md`, `wiki/transcription__.md` e os prints em `/home/marcelo/Pictures/Screenshots/rocket/php/1.1-microservicos-rabbit-mq`.

## Fase 0: Fonte da verdade (OCR) — concluída

Os 87 prints foram lidos e consolidados em `notas-github/aulas/rocket/php/microservicos-rabbit-mq/`:

| Arquivo | Conteúdo |
|---|---|
| `ec-microservices-rabbitmq.md` | README mostrado na aula |
| `codigo-php.md` | Código escrito na aula (publicação, `NotificationCommand`, `BrokerInterface`, `RabbitMQ`, `BrokerProvider`) |
| `codigo-terminal.md` | `docker ps`, `mysql`, `composer require`, `docker container inspect`, `php artisan notification` |
| `estruturas-de-dados.md` | Tabelas `users` e `transactions`, fila, payload |
| `estruturas-de-diretorios.md` | Árvores de diretórios |
| `contexto.md` | Slides, painel do RabbitMQ, Postman, Loggly, `.env.example` |
| `README.md` | Índice, com divergências e lacunas conhecidas |

**Regra de conflito:** onde print e `wiki/wiki.md` divergirem, vale o print; a wiki só serve de pista, pois o código dela foi reconstruído da narração. Exceções já decididas: banco `pay_db`, fila `notification_queue`, rota `POST`.

**Lacunas dos prints a preencher no projeto novo:** `docker-compose.yml`, `Dockerfile`, migrations, seeders, rotas, controllers, `run.sh`, `stop.sh`.

## Fase 1: Spec (o que o sistema faz)

| ID | Requisito |
|---|---|
| R1 | `POST /transaction` (`ms-transaction`, porta 8004) persiste a transação e responde "criada com sucesso" |
| R2 | A transação publica `{transaction, name, email, value}` (`name` e `email` do usuário `payee`) na fila `notification_queue`, sem esperar resposta |
| R3 | `php artisan notification` (`ms-notification`, porta 8005) consome a fila, em ordem FIFO |
| R4 | `POST /notification/send` no `ms-notification` (a aula usou `GET` com body JSON) grava a notificação fake no Loggly (canal `Notification`, nível `INFO`), com o token vindo do `.env`. Na aula o consumidor só imprime a mensagem (`Printing: ...`); ligá-lo ao envio é o desafio final, fora de escopo |
| R5 | Se o `ms-notification` estiver fora do ar, as mensagens ficam em *Ready* e são entregues quando ele volta |
| R6 | O seed cria 10 usuários e **50 transações** |
| R7 | O arquivo `client.http` traz `POST /transaction` com `payer`/`payee`/`value` |
| R8 | O broker fica atrás de `BrokerInterface`, com `RabbitMQ` como implementação, registrada no provider |

Também na spec:
- Portas: 8004, 8005, 5672 (AMQP) e 15672 (painel); login `admin`/`admin`.
- Fila `notification_queue` durável (`durable=true`: o RabbitMQ 4 recusa fila não durável e não exclusiva, ao contrário do 3.9 da aula) e consumo com `no_ack=true` (como na aula). As mensagens não são persistentes: se o RabbitMQ reiniciar, elas se perdem; o R5 só vale com o broker de pé.
- Fora de escopo: validação de saldo/usuário e o desafio de refatorar o `ms-notification`.

## Fase 2: Design (como)

- **Estrutura:** três diretórios (`ms-transaction`, `ms-queues`, `ms-notification`), um `docker-compose` por diretório e os scripts `run.sh` e `stop.sh`.
- **Containers** (6, como no `docker ps` da aula): `ms-queues` (rabbitmq:4-management-alpine, 5672 e 15672, no diretório `ms-queues`), `transaction-app` e `transaction-nginx` (porta 8004), `transaction-db` (mysql:8.4) e `notification-app` e `notification-nginx` (porta 8005); nginx 1.27-alpine.
- **Rede:** como cada `docker-compose` cria a própria rede (`ms-queues_default`), declarar uma rede externa compartilhada pelos três e usar o nome do serviço como host do RabbitMQ, no lugar do IP de gateway da aula (`localhost` falha dentro do container). Registrar como decisão de design.
- **Stack:** PHP 8.4, Lumen 10.0.4, MySQL 8.4, RabbitMQ 4 management-alpine (a aula usou PHP 8.0.10, Lumen 8.2.4, MySQL 5.7 e RabbitMQ 3.9.4) e `php-amqplib`.
- **Dados:** schemas de `users` e `transactions` vindos dos prints; banco `pay_db`.
- **Contratos:** payload JSON da fila, body do `POST /transaction` e `NotificationCommand` (`$name='notification'`, registrado no `Kernel` com `\` inicial: `\App\Console\Commands\NotificationCommand::class`).
- **Decisões:** `queue_declare('notification_queue', false, false, false, false)`, exchange vazio e `json_encode` do payload.

## Fase 3: Tarefas (ordem de implementação)

Cada tarefa tem critério de aceite e referência ao requisito:

1. Esqueleto Docker dos 3 serviços, com `run.sh` e `stop.sh`.
2. `ms-queues`: container RabbitMQ com painel em `localhost:15672` e rede compartilhada (R5).
3. `ms-transaction`: migrations, seed de 10 usuários (pode incluir um chamado Lucas) e 50 transações, rota e `TransactionService` (R1, R6).
4. `client.http` (R7). A transação 51 vem desta requisição, não do seed.
5. `ms-notification`: rota `POST /notification/send` e log no Loggly (R4).
6. Publicação *v1*, tudo num método, com a rede compartilhada (R2).
7. `NotificationCommand` com registro no `Kernel` (R3).
8. Refatoração: `BrokerInterface`, `RabbitMQ`, `BrokerProvider` e registro em `bootstrap/app.php` (R8).
9. Teste de resiliência: parar o consumidor, publicar, voltar e conferir a entrega (R5).

## Fase 4: Validação

- Checklist com um item por requisito.
- Depois do `POST` do `client.http`, a transação 51 aparece no `select * from transactions`.
- A fila passa de *Ready* para 0 depois do consumo.
- O log chega no Loggly.
- Cada tarefa da Fase 3 fecha com um critério de aceite explícito (comando ou chamada e saída esperada), tomado de `codigo-terminal.md`.
- Resultado conferido contra a seção 11 da wiki ("Resultado").

## Riscos

- Leituras de imagem que falham em silêncio (mitigação: tabela de conferência).
- Divergências entre wiki e prints.
- Imagens Docker antigas (PHP 8.0, MySQL 5.7) podem exigir ajustes no Dockerfile.
- Token do Loggly: sem conta, fallback para log em arquivo.
