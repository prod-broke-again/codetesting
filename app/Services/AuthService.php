<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    /**
     * Создать пользователя через Socialite
     */
    public function createOrUpdateUserFromSocialite($socialiteUser, string $provider): User
    {
        $user = User::updateOrCreate(
            [
                'email' => $socialiteUser->getEmail(),
            ],
            [
                'name' => $socialiteUser->getName(),
                'avatar' => $socialiteUser->getAvatar(),
                'email_verified_at' => now(),
                $provider . '_id' => $socialiteUser->getId(),
            ]
        );

        return $user;
    }

    /**
     * Создать пользователя через email/пароль
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Попытка входа через email/пароль
     */
    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Войти как пользователь
     */
    public function login(User $user): void
    {
        Auth::login($user);
    }

    /**
     * Выйти из системы
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Получить текущего пользователя
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Проверить, авторизован ли пользователь
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Создать API токен для пользователя
     */
    public function createApiToken(User $user, string $name = 'default'): string
    {
        return $user->createToken($name)->plainTextToken;
    }

    /**
     * Удалить все токены пользователя
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Связать fingerprint с пользователем
     */
    public function linkFingerprintToUser(string $fingerprintHash, User $user): void
    {
        $user->fingerprints()->updateOrCreate(
            ['fingerprint_hash' => $fingerprintHash],
            [
                'user_id' => $user->id,
                'last_seen_at' => now(),
            ]
        );
    }

    /**
     * Получить связанные сниппеты по fingerprint
     */
    public function getRelatedSnippetsByFingerprint(string $fingerprintHash): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Code::whereHas('fingerprint', function ($query) use ($fingerprintHash) {
            $query->where('fingerprint_hash', $fingerprintHash);
        })->get();
    }
} 