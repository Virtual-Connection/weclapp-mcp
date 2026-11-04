<?php

declare(strict_types=1);

namespace WeclappMcp;

use Mcp\Server;
use WeclappMcp\Tool\PingTool;

final class Bootstrap
{
    public static function createServer(): Server
    {
        return Server::builder()
            ->setServerInfo('weclapp-mcp', '0.1.0')
            ->addTool(
                [new PingTool(), 'ping'],
                name: 'ping',
                description: 'Connectivity check for the weclapp MCP server. Takes no input and returns the text "pong". Use it to verify that the server is reachable.',
            )
            ->build();
    }
}
