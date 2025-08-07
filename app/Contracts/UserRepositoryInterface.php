<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
    public function getUserStats(User $user): array;
    public function getRecentSnippets(User $user, int $limit = 5): Collection;
    public function getPopularSnippets(User $user, int $limit = 5): Collection;
}
