<?php

namespace App\Services;

use App\Models\User;
use App\Models\Code;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    /**
     * Обновить профиль пользователя
     */
    public function updateProfile(User $user, array $data): User
    {
        // Обновляем основную информацию
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Обновляем пароль если указан
        if (!empty($data['new_password'])) {
            $this->updatePassword($user, $data);
        }

        return $user->fresh();
    }

    /**
     * Обновить пароль пользователя
     */
    private function updatePassword(User $user, array $data): void
    {
        // Проверяем текущий пароль, если он задан
        if ($user->password && !Hash::check($data['current_password'] ?? '', $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Неверный текущий пароль']
            ]);
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);
    }

    /**
     * Удалить аккаунт пользователя
     */
    public function deleteAccount(User $user, string $password): bool
    {
        // Проверяем пароль
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Неверный пароль']
            ]);
        }

        // Удаляем все сниппеты пользователя
        Code::where('user_id', $user->id)->delete();
        
        // Удаляем пользователя
        return $user->delete();
    }

    /**
     * Связать fingerprint с пользователем
     */
    public function linkFingerprint(User $user, string $fingerprint): void
    {
        // Здесь можно добавить логику связывания fingerprint
        // Пока оставляем пустым, так как это уже реализовано в AuthService
    }
}
