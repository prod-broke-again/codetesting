<?php

namespace App\Contracts;

use App\Models\Code;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CodeRepositoryInterface
{
    public function create(array $data): Code;
    public function findByHash(string $hash): ?Code;
    public function findByUser(User $user): Collection;
    public function update(Code $code, array $data): Code;
    public function delete(Code $code): bool;
    public function incrementAccessCount(Code $code): void;
    public function getUserSnippetsWithFilters(User $user, array $filters, int $perPage = 20): LengthAwarePaginator;
    public function getPublicSnippets(int $limit = 10): Collection;
    public function findByLanguage(string $language, int $limit = 10): Collection;
    public function findPopular(int $limit = 10): Collection;
}
