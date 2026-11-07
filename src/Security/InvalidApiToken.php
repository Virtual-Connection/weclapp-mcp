<?php

declare(strict_types=1);

namespace WeclappMcp\Security;

use RuntimeException;

final class InvalidApiToken extends RuntimeException
{
    public static function empty(): self
    {
        return new self('The API token must not be empty.');
    }
}
