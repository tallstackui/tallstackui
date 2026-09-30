<?php

use Illuminate\Support\Facades\Blade;

it('cannot write the asset tags into the compiled view', function (string $template) {
    expect(Blade::compileString($template))
        ->not->toContain('/tallstackui/script/')
        ->not->toContain('/tallstackui/style/')
        ->toContain('<?php echo');
})->with([
    '@tallStackUiScript',
    '@tallStackUiStyle',
    '@tallStackUiSetup',
    '<tallstackui:script />',
    '<tallstackui:style />',
    '<tallstackui:setup />',
]);

it('can render the script tags', function (string $template) {
    expect(Blade::render($template))
        ->toContain('<script type="module" src="/tallstackui/script/')
        ->not->toContain('/tallstackui/style/tallstackui.css');
})->with([
    '@tallStackUiScript',
    '<tallstackui:script />',
]);

it('can render the style tag', function (string $template) {
    expect(Blade::render($template))
        ->toContain('<link href="/tallstackui/style/tallstackui.css" rel="stylesheet" type="text/css">')
        ->not->toContain('<script');
})->with([
    '@tallStackUiStyle',
    '<tallstackui:style />',
]);

it('can render the script and the style tags together', function (string $template) {
    expect(Blade::render($template))
        ->toContain('<script type="module" src="/tallstackui/script/')
        ->toContain('<link href="/tallstackui/style/tallstackui.css" rel="stylesheet" type="text/css">');
})->with([
    '@tallStackUiSetup',
    '<tallstackui:setup />',
]);
