<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Показать единую страницу авторизации
     */
    public function showAuth()
    {
        return Inertia::render('Auth/Auth', [
            'title' => 'Авторизация',
            'description' => 'Войдите в систему или создайте новый аккаунт'
        ]);
    }

    /**
     * Обработка входа
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            
            if ($this->authService->attemptLogin($credentials)) {
                $user = $this->authService->getCurrentUser();
                
                // Связываем fingerprint с пользователем
                if ($fingerprint = $request->header('X-Fingerprint')) {
                    $this->authService->linkFingerprintToUser($fingerprint, $user);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Вы успешно вошли в систему',
                    'user' => $user
                ]);
            }
            
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.']
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неверные учетные данные',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка входа в систему'
            ], 500);
        }
    }

    /**
     * Обработка регистрации
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Проверяем, не существует ли уже пользователь с таким email
            if ($this->authService->getCurrentUser() || \App\Models\User::where('email', $data['email'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пользователь с таким email уже существует'
                ], 422);
            }
            
            $user = $this->authService->createUser($data);
            $this->authService->login($user);
            
            // Связываем fingerprint с пользователем
            if ($fingerprint = $request->header('X-Fingerprint')) {
                $this->authService->linkFingerprintToUser($fingerprint, $user);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Аккаунт успешно создан',
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка создания аккаунта'
            ], 500);
        }
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout();
            
            return response()->json([
                'success' => true,
                'message' => 'Вы успешно вышли из системы'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка выхода из системы'
            ], 500);
        }
    }

    /**
     * Получить текущего пользователя
     */
    public function me()
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
            'user' => $user
        ]);
    }
}
