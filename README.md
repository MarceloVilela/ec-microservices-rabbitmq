# Adicionando comunicação entre microsserviços PHP com RabbitMQ

Este repositório consiste em um boilerplate (ponto de partida) para estudar a comunicação entre microsserviços PHP com RabbitMQ.

Stack: PHP 8.4, Lumen 10, MySQL 8.4, RabbitMQ 4 e Nginx 1.27.

Requisitos:

- Docker
- Docker-compose

## Fazendo o setup do projeto localmente

Clone o repositório:

```
git clone git@github.com:MarceloVilela/ec-microservices-rabbitmq.git
```

Entre na pasta do projeto:

```
cd ec-microservices-rabbitmq
```

Execute o arquivo `run.sh`:

```
./run.sh
```

PS: este arquivo precisa ter permissão de execução para rodar corretamente. Para saber se ele está com a permissão correta, digite `ls -la`. O resultado será como abaixo.

```
-rwxr-xr-x  1 root  root  133 11 Set 15:08 run.sh
```

A letra `x` no final da primeira sequência de letras representa a permissão de execução. Caso tenha um `-` no lugar da letra, digite `chmod +x run.sh`.

Na primeira execução o `run.sh` também cria o `.env` de cada serviço, instala as dependências (`composer install`) e roda as migrations e o seed do `ms-transaction` (10 usuários e 50 transações). O seed só popula o banco se ele estiver vazio. Para recriar tudo do zero: `./run.sh ms-transaction php artisan migrate:fresh --seed`.

Após esses passos, seu projeto deverá estar rodando perfeitamente! :)

As portas (8004, 8005, 3308, 5672 e 15672) ficam expostas só em `127.0.0.1`.

Para acessar o serviço `ms-transaction`:

```
http://127.0.0.1:8004
```

Para acessar o serviço `ms-notification`:

```
http://127.0.0.1:8005
```

Para acessar o serviço `ms-queues`:

```
http://127.0.0.1:15672

User: admin
Pass: admin
```

## Derrubando todos os serviços

Se quiser derrubar todos os serviços, basta executar o arquivo `stop.sh`:

```
./stop.sh
```

PS: o mesmo vale para esse arquivo em relação à permissão para execução verificada no arquivo `run.sh`.

## Rodando comandos nos serviços

O host não precisa ter PHP nem Composer. Para rodar um comando no container `app` de um serviço:

```
./run.sh ms-transaction php artisan about
```

## Testando o fluxo

```
curl -X POST localhost:8004/transaction -H 'Content-Type: application/json' -d '{"payer":5,"payee":10,"value":53}'
docker exec notification-app php artisan notification    # consome a fila (Ctrl+C para sair)
curl -X POST localhost:8005/notification/send -H 'Content-Type: application/json' -d '{"transaction":1,"name":"Fulano","email":"f@example.com","value":10}'
```

O `ms-notification` grava o log no Loggly se `LOGGLY_TOKEN` estiver em `ms-notification/src/.env`; sem token, grava em `ms-notification/src/storage/logs/notification.log`.
