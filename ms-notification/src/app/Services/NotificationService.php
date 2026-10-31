<?php

namespace App\Services;

use Monolog\Handler\LogglyHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class NotificationService
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('Notification');

        $token = env('LOGGLY_TOKEN');

        // sem token, a notificação "enviada" vai para um arquivo local
        $this->logger->pushHandler(
            $token
                ? new LogglyHandler($token, Level::Info)
                : new StreamHandler(storage_path('logs/notification.log'), Level::Info)
        );
    }

    public function send(array $data): void
    {
        $this->logger->info(json_encode([
            'send_to' => $data['email'],
            'transaction' => $data['transaction'],
            'message' => sprintf(
                '%s, you just received a payment. The amount of it is %s',
                $data['name'],
                $data['value']
            ),
        ]));
    }
}
