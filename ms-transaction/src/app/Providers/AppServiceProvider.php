<?php

namespace App\Providers;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Services\Interfaces\TransactionServiceInterface;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
    }
}
