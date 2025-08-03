<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Показать форму входа
     */
    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login', [
            'title' => 'Вход в систему',
            'description' => 'Войдите в свой аккаунт для доступа к премиум функциям'
        ]);
    }

    /**
     * Показать форму регистрации
     */
    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth/Register', [
            'title' => 'Регистрация',
            'description' => 'Создайте аккаунт для доступа к премиум функциям'
        ]);
    }

    /**
     * Войти в систему
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $remember = $request->boolean('remember', false);

            if ($this->authService->attemptLogin($credentials)) {
                $user = $this->authService->getCurrentUser();
                
                // Связываем fingerprint если есть
                $fingerprintHash = $request->header('X-Fingerprint');
                if ($fingerprintHash && $user) {
                    $this->authService->linkFingerprintToUser($fingerprintHash, $user);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Вход выполнен успешно!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Неверный email или пароль'
            ], 401);

        } catch (\Exception $e) {
            Log::error('Ошибка входа в систему', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка входа в систему'
            ], 500);
        }
    }

    /**
     * Зарегистрироваться
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->createUser($request->validated());
            $this->authService->login($user);

            // Связываем fingerprint если есть
            $fingerprintHash = $request->header('X-Fingerprint');
            if ($fingerprintHash) {
                $this->authService->linkFingerprintToUser($fingerprintHash, $user);
            }

            return response()->json([
                'success' => true,
                'message' => 'Регистрация выполнена успешно!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Ошибка регистрации', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка регистрации'
            ], 500);
        }
    }

    /**
     * Выйти из системы
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Выход выполнен успешно!'
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка выхода из системы', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка выхода из системы'
            ], 500);
        }
    }

    /**
     * Получить информацию о текущем пользователе
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->getCurrentUser();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не авторизован'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'has_premium' => $user->hasPremiumAccess(),
            ]
        ]);
    }
}
