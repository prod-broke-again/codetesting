<?php

namespace App\Enums;

enum SnippetPrivacy: string
{
    case PRIVATE = 'private';
    case UNLISTED = 'unlisted';
    case PUBLIC = 'public';

    public function label(): string
    {
        return match($this) {
            self::PRIVATE => 'Приватный',
            self::UNLISTED => 'Непубличный',
            self::PUBLIC => 'Публичный',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::PRIVATE => 'Доступен только владельцу',
            self::UNLISTED => 'Доступен по прямой ссылке',
            self::PUBLIC => 'Доступен всем пользователям',
        };
    }

    public function isPublic(): bool
    {
        return $this === self::PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this === self::PRIVATE;
    }

    public function isUnlisted(): bool
    {
        return $this === self::UNLISTED;
    }
} 