<?php

declare(strict_types=1);

namespace WeclappMcp\Security;

use RuntimeException;

final class InvalidHostName extends RuntimeException
{
    public static function fromInput(string $input): self
    {
        return new self(sprintf('"%s" is not a valid host name.', $input));
    }
}
