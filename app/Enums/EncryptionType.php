<?php

namespace App\Enums;

enum EncryptionType: string
{
    case LARAVEL = 'laravel';
    case AES = 'aes';
    case NONE = 'none';

    public function label(): string
    {
        return match($this) {
            self::LARAVEL => 'Laravel Encryption',
            self::AES => 'AES-256-CBC',
            self::NONE => 'Без шифрования',
        };
    }

    public function getStrategyClass(): string
    {
        return match($this) {
            self::LARAVEL => \App\Services\Encryption\LaravelEncryptionStrategy::class,
            self::AES => \App\Services\Encryption\AesEncryptionStrategy::class,
            self::NONE => throw new \InvalidArgumentException('No encryption strategy for NONE type'),
        };
    }
}
