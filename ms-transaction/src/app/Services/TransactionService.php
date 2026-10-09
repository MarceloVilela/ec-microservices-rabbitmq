<?php

namespace App\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use App\Support\Messages;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private TransactionRepositoryInterface $repository
    ) {
    }

    public function process(array $data): string
    {
        $this->repository->persist($data);

        return Messages::TRANSACTION_SUCCESSFULLY;
    }
}
