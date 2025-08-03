<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CodeController extends Controller
{
    /**
     * Показать страницу просмотра сниппета
     */
    public function show(string $hash)
    {
        $code = Code::where('hash', $hash)->first();

        if (!$code) {
            abort(404, 'Сниппет не найден');
        }

        // Увеличиваем счетчик просмотров
        $code->incrementAccessCount();

        return Inertia::render('CodeView', [
            'hash' => $hash,
            'snippet' => [
                'id' => $code->id,
                'hash' => $code->hash,
                'content' => $code->is_encrypted ? $this->decryptContent($code->content) : $code->content,
                'language' => $code->language,
                'theme' => $code->theme,
                'is_encrypted' => $code->is_encrypted,
                'access_count' => $code->access_count,
                'created_at' => $code->created_at,
                'expires_at' => $code->expires_at,
                'is_guest' => $code->is_guest
            ]
        ]);
    }

    /**
     * Расшифровать контент сниппета
     */
    private function decryptContent(string $encryptedContent): string
    {
        try {
            return decrypt($encryptedContent);
        } catch (\Exception $e) {
            return 'Ошибка расшифровки контента';
        }
    }
}
