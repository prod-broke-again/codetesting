<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Models\Code;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        // Удаляем все сниппеты пользователя
        Code::where('user_id', $user->id)->delete();
        
        return $user->delete();
    }

    public function getUserStats(User $user): array
    {
        return [
            'total_snippets' => Code::where('user_id', $user->id)->count(),
            'public_snippets' => Code::where('user_id', $user->id)->where('privacy', 'public')->count(),
            'private_snippets' => Code::where('user_id', $user->id)->where('privacy', 'private')->count(),
            'unlisted_snippets' => Code::where('user_id', $user->id)->where('privacy', 'unlisted')->count(),
            'total_views' => Code::where('user_id', $user->id)->sum('access_count'),
            'encrypted_snippets' => Code::where('user_id', $user->id)->where('is_encrypted', true)->count(),
        ];
    }

    public function getRecentSnippets(User $user, int $limit = 5): Collection
    {
        return Code::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPopularSnippets(User $user, int $limit = 5): Collection
    {
        return Code::where('user_id', $user->id)
            ->orderBy('access_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
