<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Перенаправить на Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    /**
     * Обработать callback от Google
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $socialiteUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->user();
            $user = $this->authService->createOrUpdateUserFromSocialite($socialiteUser, 'google');
            $this->authService->login($user);

            // Связываем fingerprint если есть
            $fingerprintHash = $request->header('X-Fingerprint');
            if ($fingerprintHash) {
                $this->authService->linkFingerprintToUser($fingerprintHash, $user);
            }

            return redirect()->intended('/dashboard')->with('message', 'Вход через Google выполнен успешно!');

        } catch (\Exception $e) {
            Log::error('Ошибка входа через Google', [
                'error' => $e->getMessage()
            ]);

            return redirect('/auth')->withErrors(['email' => 'Ошибка входа через Google']);
        }
    }

    /**
     * Перенаправить на GitHub OAuth
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')
            ->redirectUrl(config('services.github.redirect'))
            ->redirect();
    }

    /**
     * Обработать callback от GitHub
     */
    public function handleGithubCallback(Request $request)
    {
        try {
            $socialiteUser = Socialite::driver('github')
                ->redirectUrl(config('services.github.redirect'))
                ->user();
            $user = $this->authService->createOrUpdateUserFromSocialite($socialiteUser, 'github');
            $this->authService->login($user);

            // Связываем fingerprint если есть
            $fingerprintHash = $request->header('X-Fingerprint');
            if ($fingerprintHash) {
                $this->authService->linkFingerprintToUser($fingerprintHash, $user);
            }

            return redirect()->intended('/dashboard')->with('message', 'Вход через GitHub выполнен успешно!');

        } catch (\Exception $e) {
            Log::error('Ошибка входа через GitHub', [
                'error' => $e->getMessage()
            ]);

            return redirect('/auth')->withErrors(['email' => 'Ошибка входа через GitHub']);
        }
    }

    /**
     * Получить связанные сниппеты по fingerprint
     */
    public function getRelatedSnippets(Request $request)
    {
        try {
            $fingerprintHash = $request->header('X-Fingerprint');
            
            if (!$fingerprintHash) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fingerprint не найден'
                ], 400);
            }

            $snippets = $this->authService->getRelatedSnippetsByFingerprint($fingerprintHash);

            return response()->json([
                'success' => true,
                'data' => $snippets,
                'count' => $snippets->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка получения связанных сниппетов', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка получения связанных сниппетов'
            ], 500);
        }
    }
}
