<?php

declare(strict_types=1);

use WeclappMcp\Config\Environment;
use WeclappMcp\Config\MissingEnvironmentVariable;

test('require returns the value of a set variable', function (): void {
    $environment = new Environment(['WECLAPP_HOST' => 'example.test']);

    expect($environment->require('WECLAPP_HOST'))->toBe('example.test');
});

test('require trims surrounding whitespace', function (): void {
    $environment = new Environment(['WECLAPP_HOST' => '  example.test  ']);

    expect($environment->require('WECLAPP_HOST'))->toBe('example.test');
});

test('require throws when the variable is missing', function (): void {
    $environment = new Environment([]);

    $environment->require('WECLAPP_HOST');
})->throws(MissingEnvironmentVariable::class, 'WECLAPP_HOST');

test('require throws when the variable is empty or only whitespace', function (): void {
    $environment = new Environment(['WECLAPP_HOST' => '   ']);

    $environment->require('WECLAPP_HOST');
})->throws(MissingEnvironmentVariable::class);

test('the error message names the variable but never a value', function (): void {
    $environment = new Environment(['WECLAPP_TOKEN' => '']);

    try {
        $environment->require('WECLAPP_TOKEN');
    } catch (MissingEnvironmentVariable $exception) {
        expect($exception->getMessage())->toContain('WECLAPP_TOKEN');

        return;
    }

    throw new LogicException('Expected MissingEnvironmentVariable to be thrown.');
});
