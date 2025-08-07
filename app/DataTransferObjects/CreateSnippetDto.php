<?php

namespace App\DataTransferObjects;

use App\Enums\ProgrammingLanguage;

readonly class CreateSnippetDto
{
    public function __construct(
        public string $content,
        public ProgrammingLanguage $language,
        public string $theme,
        public bool $isEncrypted = false,
        public ?string $expiresAt = null,
        public string $privacy = 'public'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['content'],
            language: ProgrammingLanguage::from($data['language']),
            theme: $data['theme'],
            isEncrypted: $data['is_encrypted'] ?? false,
            expiresAt: $data['expires_at'] ?? null,
            privacy: $data['privacy'] ?? 'public'
        );
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'language' => $this->language->value,
            'theme' => $this->theme,
            'is_encrypted' => $this->isEncrypted,
            'expires_at' => $this->expiresAt,
            'privacy' => $this->privacy,
        ];
    }
}
