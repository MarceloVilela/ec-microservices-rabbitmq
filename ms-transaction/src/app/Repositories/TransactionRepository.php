<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function persist(array $data): Transaction
    {
        return Transaction::create($data);
    }
}
