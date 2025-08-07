<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Http\Resources\CodeResource;
use App\Models\Code;
use App\Services\CodeService;
use App\Contracts\CodeRepositoryInterface;
use App\DataTransferObjects\CreateSnippetDto;
use App\ValueObjects\SnippetHash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function __construct(
        private CodeService $codeService,
        private CodeRepositoryInterface $codeRepository
    ) {}

    /**
     * Создать новый сниппет
     */
    public function store(CreateSnippetRequest $request): JsonResponse
    {
        try {
            $dto = CreateSnippetDto::fromArray($request->validated());
            $code = $this->codeService->createSnippet(
                $dto->toArray(),
                $request->user()
            );

            $message = $code->is_guest
                ? 'Сниппет создан! Сохраните токен для редактирования.'
                : 'Сниппет создан успешно!';

            return $this->successResponse(
                data: new CodeResource($code),
                message: $message,
                status: 201
            );
        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Ошибка создания сниппета',
                extraData: [
                    'user_id' => $request->user()?->id,
                    'data' => $request->except(['content'])
                ]
            );
        }
    }

    /**
     * Показать сниппет
     */
    public function show(string $hash): JsonResponse
    {
        try {
            $snippetHash = SnippetHash::fromString($hash);
            $code = $this->codeRepository->findByHash($snippetHash->value);

            if (!$code) {
                return $this->errorResponse(
                    message: 'Сниппет не найден',
                    status: 404
                );
            }

            // Проверяем доступ
            if (!$this->codeService->canAccess($code, request()->user())) {
                return $this->errorResponse(
                    message: 'Сниппет недоступен или истек срок действия',
                    status: 403
                );
            }

            // Увеличиваем счетчик просмотров
            $this->codeService->incrementAccessCount($code, request()->user());

            return $this->successResponse(
                data: new CodeResource($code)
            );
        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Ошибка получения сниппета',
                extraData: ['hash' => $hash]
            );
        }
    }

    /**
     * Обновить сниппет
     */
    public function update(UpdateSnippetRequest $request, string $hash): JsonResponse
    {
        try {
            $snippetHash = SnippetHash::fromString($hash);
            $code = $this->codeRepository->findByHash($snippetHash->value);

            if (!$code) {
                return $this->errorResponse(
                    message: 'Сниппет не найден',
                    status: 404
                );
            }

            // Проверяем права на редактирование
            $editToken = $request->input('edit_token');
            if (!$this->codeService->canEdit($code, request()->user(), $editToken)) {
                return $this->errorResponse(
                    message: 'Нет прав на редактирование сниппета',
                    status: 403
                );
            }

            $updatedCode = $this->codeService->updateSnippet($code, $request->validated());

            return $this->successResponse(
                data: new CodeResource($updatedCode),
                message: 'Сниппет обновлен успешно!'
            );

        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Ошибка обновления сниппета',
                extraData: [
                    'hash' => $hash,
                    'user_id' => request()->user()?->id
                ]
            );
        }
    }

    /**
     * Удалить сниппет
     */
    public function destroy(Request $request, string $hash): JsonResponse
    {
        try {
            $snippetHash = SnippetHash::fromString($hash);
            $code = $this->codeRepository->findByHash($snippetHash->value);

            if (!$code) {
                return $this->errorResponse(
                    message: 'Сниппет не найден',
                    status: 404
                );
            }

            // Проверяем права на удаление
            $editToken = $request->input('edit_token');
            if (!$this->codeService->canEdit($code, request()->user(), $editToken)) {
                return $this->errorResponse(
                    message: 'Нет прав на удаление сниппета',
                    status: 403
                );
            }

            $this->codeRepository->delete($code);

            return $this->successResponse(
                message: 'Сниппет удален успешно!'
            );

        } catch (\Exception $e) {
            return $this->handleException(
                exception: $e,
                context: 'Ошибка удаления сниппета',
                extraData: [
                    'hash' => $hash,
                    'user_id' => request()->user()?->id
                ]
            );
        }
    }
}
