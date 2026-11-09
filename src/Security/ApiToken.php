<?php

declare(strict_types=1);

namespace WeclappMcp\Security;

use SensitiveParameter;

final readonly class ApiToken
{
    private const string MASK = '***';

    private function __construct(private string $value) {}

    /**
     * @throws InvalidApiToken
     */
    public static function fromString(#[SensitiveParameter] string $token): self
    {
        $token = trim($token);

        if ($token === '') {
            throw InvalidApiToken::empty();
        }

        return new self($token);
    }

    public function reveal(): string
    {
        return $this->value;
    }

    /**
     * @return array<string, string>
     */
    public function __debugInfo(): array
    {
        return ['value' => self::MASK];
    }
}
