<?php

declare(strict_types=1);

use WeclappMcp\Security\HostName;
use WeclappMcp\Security\InvalidHostName;

test('a plain host name is kept as it is', function (): void {
    expect(HostName::fromString('tenant.example.test')->value())->toBe('tenant.example.test');
});

test('different spellings of the same host are normalized', function (string $input): void {
    expect(HostName::fromString($input)->value())->toBe('tenant.example.test');
})->with([
    'upper case' => ['TENANT.Example.TEST'],
    'surrounding spaces' => ['  tenant.example.test  '],
    'https scheme' => ['https://tenant.example.test'],
    'http scheme' => ['http://tenant.example.test'],
    'trailing slash' => ['tenant.example.test/'],
    'path' => ['https://tenant.example.test/webapp/api/v2/'],
    'query string' => ['tenant.example.test?x=1'],
    'trailing dot' => ['tenant.example.test.'],
    'everything at once' => ['  HTTPS://Tenant.Example.TEST/webapp/api/v2/?a=b#c  '],
]);

test('invalid input is rejected', function (string $input): void {
    HostName::fromString($input);
})->with([
    'empty' => [''],
    'only spaces' => ['   '],
    'only a scheme' => ['https://'],
    'with a port' => ['tenant.example.test:8443'],
    'with user info' => ['user@tenant.example.test'],
    'with inner spaces' => ['tenant example.test'],
    'underscore' => ['tenant_x.example.test'],
    'leading dash' => ['-tenant.example.test'],
    'empty label' => ['tenant..example.test'],
    'other scheme' => ['ftp://tenant.example.test'],
    'umlaut' => ['täst.example.test'],
])->throws(InvalidHostName::class);

test('a host hidden in the path is not mistaken for the real host', function (): void {
    expect(HostName::fromString('evil.example.test/tenant.example.test')->value())
        ->toBe('evil.example.test');
});

test('a host that only starts with an allowed host is a different host', function (): void {
    $allowed = HostName::fromString('tenant.example.test');
    $other = HostName::fromString('tenant.example.test.evil.example');

    expect($allowed->equals($other))->toBeFalse();
});

test('equals compares normalized values', function (): void {
    $a = HostName::fromString('HTTPS://Tenant.Example.Test/');
    $b = HostName::fromString('tenant.example.test');

    expect($a->equals($b))->toBeTrue();
});
