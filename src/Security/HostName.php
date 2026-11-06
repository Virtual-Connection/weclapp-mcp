<?php

declare(strict_types=1);

namespace WeclappMcp\Security;

final readonly class HostName
{
    private const int MAX_LENGTH = 253;

    private const string PATTERN = '/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?(\.[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?)*$/';

    private function __construct(private string $value) {}

    /**
     * @throws InvalidHostName
     */
    public static function fromString(string $input): self
    {
        $host = strtolower(trim($input));
        $host = (string) preg_replace('#^https?://#', '', $host);
        $host = strtok($host, '/?#');
        $host = $host === false ? '' : (string) preg_replace('/\.$/', '', $host);

        if ($host === '' || strlen($host) > self::MAX_LENGTH || preg_match(self::PATTERN, $host) !== 1) {
            throw InvalidHostName::fromInput($input);
        }

        return new self($host);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
