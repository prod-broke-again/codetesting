<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Http\Resources\CodeResource;
use App\Models\Code;
use App\Services\CodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CodeController extends Controller
{
    public function __construct(
        private CodeService $codeService
    ) {}

    /**
     * Создать новый сниппет
     */
    public function store(CreateSnippetRequest $request): JsonResponse
    {
        try {
            $code = $this->codeService->createSnippet(
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => new CodeResource($code),
                'message' => $code->is_guest 
                    ? 'Сниппет создан! Сохраните токен для редактирования.'
                    : 'Сниппет создан успешно!'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Ошибка создания сниппета', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'data' => $request->except(['content'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка создания сниппета'
            ], 500);
        }
    }

    /**
     * Показать сниппет
     */
    public function show(string $hash): JsonResponse
    {
        $code = $this->codeService->findByHash($hash);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Сниппет не найден'
            ], 404);
        }

        // Проверяем доступ
        if (!$this->codeService->canAccess($code, request()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Сниппет недоступен или истек срок действия'
            ], 403);
        }

        // Увеличиваем счетчик просмотров
        $this->codeService->incrementAccessCount($code);

        return response()->json([
            'success' => true,
            'data' => new CodeResource($code)
        ]);
    }

    /**
     * Обновить сниппет
     */
    public function update(UpdateSnippetRequest $request, string $hash): JsonResponse
    {
        $code = $this->codeService->findByHash($hash);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Сниппет не найден'
            ], 404);
        }

        // Проверяем права на редактирование
        $editToken = $request->input('edit_token');
        if (!$this->codeService->canEdit($code, request()->user(), $editToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Нет прав на редактирование сниппета'
            ], 403);
        }

        try {
            $updatedCode = $this->codeService->updateSnippet($code, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new CodeResource($updatedCode),
                'message' => 'Сниппет обновлен успешно!'
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка обновления сниппета', [
                'error' => $e->getMessage(),
                'hash' => $hash,
                'user_id' => request()->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка обновления сниппета'
            ], 500);
        }
    }

    /**
     * Удалить сниппет
     */
    public function destroy(Request $request, string $hash): JsonResponse
    {
        $code = $this->codeService->findByHash($hash);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Сниппет не найден'
            ], 404);
        }

        // Проверяем права на удаление
        $editToken = $request->input('edit_token');
        if (!$this->codeService->canEdit($code, request()->user(), $editToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Нет прав на удаление сниппета'
            ], 403);
        }

        try {
            $this->codeService->deleteSnippet($code);

            return response()->json([
                'success' => true,
                'message' => 'Сниппет удален успешно!'
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка удаления сниппета', [
                'error' => $e->getMessage(),
                'hash' => $hash,
                'user_id' => request()->user()?->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка удаления сниппета'
            ], 500);
        }
    }
}
