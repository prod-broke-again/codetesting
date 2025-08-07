<?php

namespace App\Services\Encryption;

use App\Contracts\EncryptionStrategyInterface;
use Illuminate\Support\Facades\Crypt;
use Exception;

class LaravelEncryptionStrategy implements EncryptionStrategyInterface
{
    public function encrypt(string $data): string
    {
        return Crypt::encryptString($data);
    }

    public function decrypt(string $encryptedData): string
    {
        try {
            return Crypt::decryptString($encryptedData);
        } catch (Exception $e) {
            throw new Exception('Ошибка расшифровки контента: ' . $e->getMessage());
        }
    }

    public function getName(): string
    {
        return 'Laravel';
    }
}
