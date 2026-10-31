<?php

namespace App\Providers;

use App\Brokers\Interfaces\BrokerInterface;
use App\Brokers\RabbitMQ;
use Illuminate\Support\ServiceProvider;

class BrokerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BrokerInterface::class, RabbitMQ::class);
    }
}
