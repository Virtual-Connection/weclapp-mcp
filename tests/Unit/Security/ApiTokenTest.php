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

test('the parameter of fromString is marked sensitive', function (): void {
    $parameter = new ReflectionMethod(ApiToken::class, 'fromString')->getParameters()[0];

    expect($parameter->getAttributes(SensitiveParameter::class))->not->toBeEmpty();
});
