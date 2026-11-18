<?php

declare(strict_types=1);

use WeclappMcp\Security\ApiToken;
use WeclappMcp\Security\InvalidApiToken;

test('reveal returns the value without surrounding spaces', function (): void {
    $token = ApiToken::fromString('  secret-test-value  ');

    expect($token->reveal())->toBe('secret-test-value');
});

test('rejects empty or blank text', function (string $text): void {
    ApiToken::fromString($text);
})->with([
    'empty' => [''],
    'only spaces' => ['   '],
])->throws(InvalidApiToken::class);

test('var_dump does not show the value', function (): void {
    $token = ApiToken::fromString('secret-test-value');

    ob_start();
    var_dump($token);
    $output = (string) ob_get_clean();

    expect($output)->not->toContain('secret-test-value');
});

test('export representations hide the value', function (): void {
    $token = ApiToken::fromString('secret-test-value');

    expect(print_r($token, true))->not->toContain('secret-test-value')
        ->and(var_export($token, true))->not->toContain('secret-test-value')
        ->and(json_encode($token))->toBe('{"value":"***"}');
});

test('serialization is refused without exposing the value', function (): void {
    $token = ApiToken::fromString('secret-test-value');

    try {
        serialize($token);
    } catch (LogicException $exception) {
        expect((string) $exception)
            ->toContain('An API token must not be serialized.')
            ->not->toContain('secret-test-value');

        return;
    }

    throw new LogicException('Serialization should have been refused.');
});

test('a crafted payload cannot restore the value', function (): void {
    $data = 'a:1:{s:5:"value";s:17:"secret-test-value";}';
    $payload = 'C:28:"WeclappMcp\\Security\\ApiToken":'.strlen($data).':{'.$data.'}';

    expect(fn () => unserialize($payload))->toThrow(LogicException::class);
});

test('the parameter of fromString is marked sensitive', function (): void {
    $parameter = new ReflectionMethod(ApiToken::class, 'fromString')->getParameters()[0];

    expect($parameter->getAttributes(SensitiveParameter::class))->not->toBeEmpty();
});
