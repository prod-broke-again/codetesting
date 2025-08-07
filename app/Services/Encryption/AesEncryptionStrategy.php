<?php

namespace App\Services\Encryption;

use App\Contracts\EncryptionStrategyInterface;
use Exception;

class AesEncryptionStrategy implements EncryptionStrategyInterface
{
    private string $key;
    private string $cipher = 'AES-256-CBC';

    public function __construct(?string $key = null)
    {
        $this->key = $key ?? config('app.key');
    }

    public function encrypt(string $data): string
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, 0, $iv);
        
        if ($encrypted === false) {
            throw new Exception('Ошибка шифрования данных');
        }

        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $encryptedData): string
    {
        try {
            $data = base64_decode($encryptedData);
            $iv = substr($data, 0, 16);
            $encrypted = substr($data, 16);
            
            $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
            
            if ($decrypted === false) {
                throw new Exception('Ошибка расшифровки данных');
            }
            
            return $decrypted;
        } catch (Exception $e) {
            throw new Exception('Ошибка расшифровки контента: ' . $e->getMessage());
        }
    }

    public function getName(): string
    {
        return 'AES-256-CBC';
    }
}
