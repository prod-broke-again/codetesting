<?php

namespace App\Contracts;

interface Encryptable
{
    public function encrypt(string $data): string;
    public function decrypt(string $encryptedData): string;
}

