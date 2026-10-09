<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TransactionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionServiceInterface $service
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id',
            'value' => 'required|numeric|gt:0',
        ]);

        return response()->json(['message' => $this->service->process($data)]);
    }
}
