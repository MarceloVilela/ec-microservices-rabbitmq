# 2 — ms-queues: RabbitMQ com painel e rede compartilhada

Etapa 2 do `specs.md` (Fase 3), boilerplate. Requisito R5.

Pedido: garantir que o RabbitMQ 4 do `ms-queues` sobe com o painel em `localhost:15672` (login `admin`/`admin`) e que está na rede externa `ec-network`, alcançável pelos outros serviços pelo nome `ms-queues`.

Escopo: só subir e conferir. Sem publicar nem consumir (etapas 6 e 7).

Aceite: painel em `localhost:15672` responde e aceita o login.
