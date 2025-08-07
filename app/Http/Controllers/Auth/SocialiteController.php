<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Exception;
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

        } catch (Exception $e) {
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

        } catch (Exception $e) {
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
                return response()->json(['snippets' => []]);
            }
            
            $snippets = $this->authService->getSnippetsByFingerprint($fingerprintHash);
            
            return response()->json(['snippets' => $snippets]);
            
        } catch (Exception $e) {
            Log::error('Ошибка получения связанных сниппетов', [
                'error' => $e->getMessage(),
                'fingerprint' => $request->header('X-Fingerprint')
            ]);
            
            return response()->json(['snippets' => []]);
        }
    }
}
