<?php

namespace App\Specifications;

use App\Models\Code;
use Illuminate\Database\Eloquent\Builder;

class ActiveSnippetSpecification
{
    public function toQuery(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function isSatisfiedBy(Code $code): bool
    {
        return !$code->isExpired();
    }
}
