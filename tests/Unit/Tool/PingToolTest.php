<?php

declare(strict_types=1);

use WeclappMcp\Tool\PingTool;

test('ping tool can be instantiated', function (): void {
    $pingTool = new PingTool();
    expect($pingTool->ping())->toBe('pong');
});
