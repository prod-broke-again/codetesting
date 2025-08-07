<?php

namespace App\Contracts;

interface EncryptionStrategyInterface
{
    public function encrypt(string $data): string;
    public function decrypt(string $encryptedData): string;
    public function getName(): string;
}
