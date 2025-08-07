<?php

namespace App\DataTransferObjects;

use App\Enums\ProgrammingLanguage;
use App\Enums\SnippetPrivacy;

readonly class CreateSnippetDto
{
    public function __construct(
        public string $content,
        public ProgrammingLanguage $language,
        public SnippetPrivacy $privacy = SnippetPrivacy::PUBLIC,
        public bool $isEncrypted = false,
        public string $theme = 'vs-dark',
        public ?string $expiresAt = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['content'],
            language: ProgrammingLanguage::from($data['language']),
            privacy: isset($data['privacy']) ? SnippetPrivacy::from($data['privacy']) : SnippetPrivacy::PUBLIC,
            isEncrypted: $data['is_encrypted'] ?? false,
            theme: $data['theme'] ?? 'vs-dark',
            expiresAt: $data['expires_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'language' => $this->language->value,
            'privacy' => $this->privacy->value,
            'is_encrypted' => $this->isEncrypted,
            'theme' => $this->theme,
            'expires_at' => $this->expiresAt,
        ];
    }

    public function isGuest(): bool
    {
        return $this->privacy === SnippetPrivacy::PUBLIC && !$this->isEncrypted;
    }

    public function requiresAuthentication(): bool
    {
        return $this->isEncrypted || $this->privacy === SnippetPrivacy::PRIVATE;
    }
}
