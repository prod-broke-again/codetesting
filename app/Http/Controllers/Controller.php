<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Http\Resources\CodeResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Успешный JSON ответ
     */
    protected function successResponse(
        array|CodeResource|null $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        $response = ApiResponse::success($message, $data, $status);
        return $response->toResponse(request());
    }

    /**
     * Ошибка JSON ответ
     */
    protected function errorResponse(
        string $message = 'Error',
        int $status = 400,
        array|null $errors = null
    ): JsonResponse {
        $response = ApiResponse::error($message, $errors, $status);
        return $response->toResponse(request());
    }

    /**
     * Обработка исключений с логированием
     */
    protected function handleException(
        Throwable $exception,
        string $context = 'Unknown operation',
        array $extraData = []
    ): JsonResponse {
        $errorData = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'context' => $context,
            'extra_data' => $extraData,
            'user_id' => request()->user()?->id,
            'url' => request()->url(),
            'method' => request()->method(),
        ];

        Log::error("Exception in {$context}", $errorData);

        // В продакшене не показываем детали ошибки
        $userMessage = config('app.debug') 
            ? $exception->getMessage()
            : 'Произошла внутренняя ошибка сервера';

        return $this->errorResponse(
            message: $userMessage,
            status: 500
        );
    }

    /**
     * Проверка аутентификации пользователя
     */
    protected function requireAuth(): void
    {
        if (!request()->user()) {
            abort(401, 'Требуется авторизация');
        }
    }

    /**
     * Проверка прав доступа к сниппету
     */
    protected function requireSnippetAccess(int $snippetId): void
    {
        $user = request()->user();
        if (!$user) {
            abort(401, 'Требуется авторизация');
        }

        // Здесь можно добавить дополнительную логику проверки прав
        // Например, проверка владения сниппетом или публичного доступа
    }
}
