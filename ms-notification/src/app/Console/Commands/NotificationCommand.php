<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class NotificationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume all notification in the queue';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // o host é o nome do serviço na rede ec-network
        $connection = new AMQPStreamConnection('ms-queues', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        // durable = true: o RabbitMQ 4 recusa fila não durável e não exclusiva
        $channel->queue_declare('notification_queue', false, true, false, false);

        echo "Consuming queue: \n";

        $callback = function ($msg) {
            echo "Printing: " . $msg->body . "\n";
        };

        $channel->basic_consume('notification_queue', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
