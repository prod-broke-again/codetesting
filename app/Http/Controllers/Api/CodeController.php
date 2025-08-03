<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\Fingerprint;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Создание нового сниппета
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'language' => 'required|string|max:50',
            'theme' => 'required|string|max:50',
            'is_encrypted' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            // Генерация уникального хеша
            $hash = Code::generateHash();
            
            // Определение типа сниппета (гостевой или зарегистрированный)
            $isGuest = !auth()->check();
            
            // Шифрование контента
            $encryptedContent = $this->encryptContent($request->content, $isGuest);
            
            // Создание сниппета
            $code = Code::create([
                'hash' => $hash,
                'content' => $encryptedContent,
                'language' => $request->language,
                'theme' => $request->theme,
                'is_encrypted' => $request->boolean('is_encrypted', false),
                'user_id' => auth()->id(),
                'is_guest' => $isGuest,
                'edit_token' => $isGuest ? Code::generateEditToken() : null,
                'expires_at' => $request->expires_at,
            ]);

            // Обработка fingerprint для гостевых сниппетов
            if ($isGuest) {
                $this->handleFingerprint($code, $request);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'hash' => $code->hash,
                    'edit_token' => $code->edit_token,
                    'url' => url("/code/{$code->hash}"),
                    'message' => $isGuest 
                        ? 'Сниппет создан! Сохраните токен для редактирования.' 
                        : 'Сниппет создан успешно!'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка создания сниппета: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Просмотр сниппета
     */
    public function show(string $hash): JsonResponse
    {
        try {
            $code = Code::where('hash', $hash)->firstOrFail();
            
            // Проверка истечения срока
            if ($code->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Сниппет истек'
                ], 404);
            }

            // Увеличение счетчика просмотров
            $code->incrementAccessCount();
            
            // Расшифровка контента
            $decryptedContent = $this->decryptContent($code->content, $code->is_guest);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'hash' => $code->hash,
                    'content' => $decryptedContent,
                    'language' => $code->language,
                    'theme' => $code->theme,
                    'is_encrypted' => $code->is_encrypted,
                    'access_count' => $code->access_count,
                    'created_at' => $code->created_at,
                    'expires_at' => $code->expires_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Сниппет не найден'
            ], 404);
        }
    }

    /**
     * Обновление сниппета по токену
     */
    public function update(Request $request, string $hash): JsonResponse
    {
        $request->validate([
            'edit_token' => 'required|string',
            'content' => 'required|string|max:10000',
            'language' => 'required|string|max:50',
            'theme' => 'required|string|max:50',
        ]);

        try {
            $code = Code::where('hash', $hash)
                ->where('edit_token', $request->edit_token)
                ->firstOrFail();

            // Проверка истечения срока
            if ($code->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Сниппет истек'
                ], 400);
            }

            // Шифрование нового контента
            $encryptedContent = $this->encryptContent($request->content, $code->is_guest);
            
            // Обновление сниппета
            $code->update([
                'content' => $encryptedContent,
                'language' => $request->language,
                'theme' => $request->theme,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Сниппет обновлен успешно'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный токен или сниппет не найден'
            ], 404);
        }
    }

    /**
     * Удаление сниппета
     */
    public function destroy(Request $request, string $hash): JsonResponse
    {
        $request->validate([
            'edit_token' => 'required|string',
        ]);

        try {
            $code = Code::where('hash', $hash)
                ->where('edit_token', $request->edit_token)
                ->firstOrFail();

            $code->delete();

            return response()->json([
                'success' => true,
                'message' => 'Сниппет удален успешно'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный токен или сниппет не найден'
            ], 404);
        }
    }

    /**
     * Шифрование контента
     */
    private function encryptContent(string $content, bool $isGuest): string
    {
        if ($isGuest) {
            // Для гостевых сниппетов используем симметричное шифрование Laravel
            return Crypt::encryptString($content);
        } else {
            // Для зарегистрированных пользователей используем асимметричное шифрование
            // TODO: Реализовать с spatie/crypto
            return Crypt::encryptString($content);
        }
    }

    /**
     * Расшифровка контента
     */
    private function decryptContent(string $encryptedContent, bool $isGuest): string
    {
        if ($isGuest) {
            // Для гостевых сниппетов используем симметричное расшифрование Laravel
            return Crypt::decryptString($encryptedContent);
        } else {
            // Для зарегистрированных пользователей используем асимметричное расшифрование
            // TODO: Реализовать с spatie/crypto
            return Crypt::decryptString($encryptedContent);
        }
    }

    /**
     * Обработка fingerprint для гостевых сниппетов
     */
    private function handleFingerprint(Code $code, Request $request): void
    {
        $fingerprintHash = $request->header('X-Fingerprint');
        
        if ($fingerprintHash) {
            $fingerprint = Fingerprint::firstOrCreate(
                ['fingerprint_hash' => $fingerprintHash],
                [
                    'browser_data' => [
                        'user_agent' => $request->userAgent(),
                        'ip' => $request->ip(),
                    ],
                    'created_at' => now(),
                ]
            );

            $code->update(['fingerprint_id' => $fingerprint->id]);
        }
    }
}
