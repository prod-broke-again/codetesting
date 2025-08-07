<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Controller
{
    /**
     * Возвращает успешный JSON ответ
     */
    protected function successResponse(
        mixed $data = null, 
        string $message = 'Success', 
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Возвращает JSON ответ с ошибкой
     */
    protected function errorResponse(
        string $message = 'Error', 
        int $status = 400, 
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Обработка исключений с логированием
     */
    protected function handleException(
        Throwable $exception, 
        string $context = 'Unknown operation',
        array $extraData = []
    ): JsonResponse {
        Log::error($context, [
            'error' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            ...$extraData
        ]);

        return $this->errorResponse(
            message: 'An error occurred while processing your request',
            status: 500
        );
    }

    /**
     * Проверка авторизации пользователя
     */
    protected function requireAuth(): void
    {
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }
    }
}
