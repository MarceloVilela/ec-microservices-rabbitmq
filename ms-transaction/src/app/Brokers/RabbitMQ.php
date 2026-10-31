<?php

namespace App\Brokers;

use App\Brokers\Interfaces\BrokerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements BrokerInterface
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'ms-queues'),
            (int) env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'admin'),
            env('RABBITMQ_PASSWORD', 'admin')
        );
        $this->channel = $this->connection->channel();
    }

    public function sendToQueue(string $queue, array $payload): void
    {
        // durable = true: o RabbitMQ 4 recusa fila não durável e não exclusiva
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(json_encode($payload));
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
