<?php

declare(strict_types=1);

use Mcp\Server\Transport\StdioTransport;
use WeclappMcp\Bootstrap;

require __DIR__ . '/../vendor/autoload.php';

exit(Bootstrap::createServer()->run(new StdioTransport()));
