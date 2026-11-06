<?php

declare(strict_types=1);

namespace WeclappMcp\Config;

use RuntimeException;

final class MissingEnvironmentVariable extends RuntimeException
{
    public static function named(string $name): self
    {
        return new self(sprintf('Environment variable %s is not set or empty.', $name));
    }
}
