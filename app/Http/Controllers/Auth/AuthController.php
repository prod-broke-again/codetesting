<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\UserProfileService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UserProfileService $profileService
    ) {}

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
            
            if (!$this->authService->attemptLogin($credentials)) {
                throw ValidationException::withMessages([
                    'email' => ['Неверные учетные данные.']
                ]);
            }
            
            $user = $this->authService->getCurrentUser();
            
            if ($fingerprint = $request->header('X-Fingerprint')) {
                $this->profileService->linkFingerprint($user, $fingerprint);
            }
            
            return redirect()->intended('/')->with('message', 'Вы успешно вошли в систему');
            
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Ошибка входа в систему']);
        }
    }

    /**
     * Обработка регистрации
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            
            $user = $this->authService->createUser($data);
            $this->authService->login($user);
            
            if ($fingerprint = $request->header('X-Fingerprint')) {
                $this->profileService->linkFingerprint($user, $fingerprint);
            }
            
            return redirect('/')->with('message', 'Аккаунт успешно создан');
            
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Ошибка создания аккаунта']);
        }
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout();
            return redirect('/')->with('message', 'Вы успешно вышли из системы');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Ошибка выхода из системы']);
        }
    }

    /**
     * Получить текущего пользователя
     */
    public function me()
    {
        try {
            $user = $this->authService->getCurrentUser();
            
            if (!$user) {
                return response()->json(['authenticated' => false]);
            }
            
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['authenticated' => false]);
        }
    }
}
