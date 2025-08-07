<?php

namespace App\Services;

use App\Models\User;
use App\Models\Code;
use App\Repositories\UserRepository;
use App\Repositories\CodeRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    public function __construct(
        private UserRepository $userRepository,
        private CodeRepository $codeRepository
    ) {}

    /**
     * Получить статистику пользователя
     */
    public function getUserStats(User $user): array
    {
        return [
            'total_snippets' => $this->codeRepository->countByUser($user->id),
            'public_snippets' => $this->codeRepository->countByUserAndPrivacy($user->id, 'public'),
            'private_snippets' => $this->codeRepository->countByUserAndPrivacy($user->id, 'private'),
            'unlisted_snippets' => $this->codeRepository->countByUserAndPrivacy($user->id, 'unlisted'),
            'total_views' => $this->codeRepository->getTotalViewsByUser($user->id),
            'encrypted_snippets' => $this->codeRepository->countEncryptedByUser($user->id),
        ];
    }

    /**
     * Получить последние сниппеты пользователя
     */
    public function getRecentSnippets(User $user, int $limit = 5): array
    {
        return $this->codeRepository->getRecentByUser($user->id, $limit)->toArray();
    }

    /**
     * Обновить профиль пользователя
     */
    public function updateProfile(User $user, array $data): bool
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Обновляем пароль если указан
        if (!empty($data['new_password'])) {
            if (!$this->validateCurrentPassword($user, $data['current_password'])) {
                throw ValidationException::withMessages([
                    'current_password' => 'Неверный текущий пароль'
                ]);
            }
            
            $updateData['password'] = Hash::make($data['new_password']);
        }

        return $this->userRepository->update($user->id, $updateData);
    }

    /**
     * Удалить аккаунт пользователя
     */
    public function deleteAccount(User $user, string $password): bool
    {
        if (!$this->validateCurrentPassword($user, $password)) {
            throw ValidationException::withMessages([
                'password' => 'Неверный пароль'
            ]);
        }

        // Удаляем все сниппеты пользователя
        $this->codeRepository->deleteAllByUser($user->id);
        
        // Удаляем пользователя
        return $this->userRepository->delete($user->id);
    }

    /**
     * Проверить текущий пароль пользователя
     */
    private function validateCurrentPassword(User $user, ?string $password): bool
    {
        if (!$user->password) {
            return true; // Пользователь без пароля (социальная авторизация)
        }

        return Hash::check($password, $user->password);
    }
}
