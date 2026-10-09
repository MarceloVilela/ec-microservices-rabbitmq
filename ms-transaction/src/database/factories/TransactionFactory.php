<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'payer' => $this->faker->numberBetween(1, 5),
            'payee' => $this->faker->numberBetween(6, 10),
            'value' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
