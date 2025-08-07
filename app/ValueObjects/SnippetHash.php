<?php

namespace App\ValueObjects;

use App\Models\Code;
use Illuminate\Support\Str;
use InvalidArgumentException;

readonly class SnippetHash
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 32;

    public function __construct(public string $value)
    {
        $this->validate();
    }

    public static function generate(): self
    {
        do {
            $hash = Str::random(16);
        } while (Code::where('hash', $hash)->exists());

        return new self($hash);
    }

    public static function fromString(string $hash): self
    {
        return new self($hash);
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('Hash cannot be empty');
        }

        if (strlen($this->value) < self::MIN_LENGTH || strlen($this->value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Hash length must be between %d and %d characters', 
                    self::MIN_LENGTH, 
                    self::MAX_LENGTH
                )
            );
        }

        if (!ctype_alnum($this->value)) {
            throw new InvalidArgumentException('Hash must contain only alphanumeric characters');
        }
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(SnippetHash $other): bool
    {
        return $this->value === $other->value;
    }
}
