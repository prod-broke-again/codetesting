<?php

namespace App\Enums;

enum SnippetStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case PRIVATE = 'private';
    case PUBLIC = 'public';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Активный',
            self::EXPIRED => 'Истёк',
            self::PRIVATE => 'Приватный',
            self::PUBLIC => 'Публичный',
            self::ARCHIVED => 'Архивный',
        };
    }
}
