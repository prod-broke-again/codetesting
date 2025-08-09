<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSnippetRequest;
use App\Http\Resources\CodeResource;
use App\Services\CodeService;
use App\Contracts\CodeRepositoryInterface;
use App\ValueObjects\SnippetHash;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CodeController extends Controller
{
    public function __construct(
        private CodeService $codeService,
        private CodeRepositoryInterface $codeRepository
    ) {}

    /**
     * Создание сниппета (web)
     */
    public function store(CreateSnippetRequest $request)
    {
        try {
            $code = $this->codeService->createSnippet(
                $request->validated(),
                $request->user()
            );

            // Если клиент ожидает JSON (fetch), возвращаем JSON-ответ
            if ($request->wantsJson()) {
                return $this->successResponse(
                    data: new CodeResource($code),
                    message: 'Сниппет создан',
                    status: 201
                );
            }

            // Иначе обычный редирект (для форм без fetch)
            return redirect()->to('/code/' . $code->hash);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return $this->handleException(
                    exception: $e,
                    context: 'Создание сниппета (web-json)',
                    extraData: ['user_id' => $request->user()?->id]
                );
            }

            return back()->withErrors(['error' => 'Ошибка создания сниппета']);
        }
    }

    /**
     * Показать страницу просмотра сниппета
     */
    public function show(string $hash)
    {
        try {
            $snippetHash = SnippetHash::fromString($hash);
            $code = $this->codeRepository->findByHash($snippetHash->value);

            if (!$code) {
                abort(404, 'Сниппет не найден');
            }

            // Проверяем доступ
            if (!$this->codeService->canAccess($code, auth()->user())) {
                abort(403, 'Сниппет недоступен или истек срок действия');
            }

            // Увеличиваем счетчик просмотров
            $this->codeService->incrementAccessCount($code, auth()->user());

            $content = $code->is_encrypted 
                ? $this->codeService->decrypt($code->content) 
                : $code->content;

            return Inertia::render('CodeView', [
                'hash' => $hash,
                'snippet' => [
                    'id' => $code->id,
                    'hash' => $code->hash,
                    'content' => $content,
                    'language' => $code->language,
                    'theme' => $code->theme,
                    'is_encrypted' => $code->is_encrypted,
                    'access_count' => $code->access_count,
                    'created_at' => $code->created_at,
                    'expires_at' => $code->expires_at,
                    'is_guest' => $code->is_guest,
                    'user_id' => $code->user_id,
                    'privacy' => $code->privacy,
                ]
            ]);
        } catch (Exception $e) {
            abort(500, 'Ошибка при обработке сниппета');
        }
    }
}
