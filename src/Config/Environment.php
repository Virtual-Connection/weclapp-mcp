<?php

declare(strict_types=1);

namespace WeclappMcp\Config;

final readonly class Environment
{
    /**
     * @param array<string, string> $variables
     */
    public function __construct(private array $variables) {}

    public static function fromGlobals(): self
    {
        return new self(getenv());
    }

    /**
     * @throws MissingEnvironmentVariable
     */
    public function require(string $name): string
    {
        $value = trim($this->variables[$name] ?? '');

        if ($value === '') {
            throw MissingEnvironmentVariable::named($name);
        }

        return $value;
    }
}
