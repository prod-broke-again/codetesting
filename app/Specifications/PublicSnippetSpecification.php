<?php

namespace App\Specifications;

use App\Models\Code;
use Illuminate\Database\Eloquent\Builder;

class PublicSnippetSpecification
{
    public function toQuery(Builder $query): Builder
    {
        return $query->where('privacy', 'public');
    }

    public function isSatisfiedBy(Code $code): bool
    {
        return $code->privacy === 'public';
    }
}
