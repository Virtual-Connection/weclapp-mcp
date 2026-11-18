<?php

declare(strict_types=1);

namespace WeclappMcp\Security;

use JsonSerializable;
use LogicException;
use SensitiveParameter;
use SensitiveParameterValue;

final readonly class ApiToken implements JsonSerializable
{
    private const string MASK = '***';

    private function __construct(private SensitiveParameterValue $value) {}

    /**
     * @throws InvalidApiToken
     */
    public static function fromString(#[SensitiveParameter] string $token): self
    {
        $token = trim($token);

        if ($token === '') {
            throw InvalidApiToken::empty();
        }

        return new self(new SensitiveParameterValue($token));
    }

    public function reveal(): string
    {
        $value = $this->value->getValue();

        if (! is_string($value)) {
            throw new LogicException('The API token must be a string.');
        }

        return $value;
    }

    /**
     * @return array{value: string}
     */
    public function __debugInfo(): array
    {
        return ['value' => self::MASK];
    }

    /**
     * @return array{value: string}
     */
    public function jsonSerialize(): array
    {
        return ['value' => self::MASK];
    }

    /**
     * @return array<string, string>
     */
    public function __serialize(): array
    {
        throw new LogicException('An API token must not be serialized.');
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        throw new LogicException('An API token must not be serialized.');
    }
}
