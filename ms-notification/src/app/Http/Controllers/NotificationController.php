<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $service
    ) {
    }

    public function send(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'transaction' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'value' => 'required|numeric',
        ]);

        $this->service->send($data);

        return response()->json(['data' => 'Notification sent successfully.']);
    }
}
