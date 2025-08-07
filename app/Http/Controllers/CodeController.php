<?php

namespace App\Http\Controllers;

use App\Models\Code;
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
                    'is_guest' => $code->is_guest
                ]
            ]);
        } catch (Exception $e) {
            abort(500, 'Ошибка при обработке сниппета');
        }
    }
}
