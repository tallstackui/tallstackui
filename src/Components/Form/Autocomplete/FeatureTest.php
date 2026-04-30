<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with array items', function () {
    $component = <<<'HTML'
    <x-autocomplete label="City" hint="Pick one" :items="[
        ['value' => 'São Paulo'],
        ['value' => 'Rio de Janeiro'],
    ]" />
HTML;

    expect($component)->render()
        ->toContain('City')
        ->toContain('Rio de Janeiro')
        ->toContain('tallstackui_autocomplete');
});

it('can render with collection items', function () {
    $component = <<<'HTML'
    <x-autocomplete :items="collect([
        ['value' => 'Foo'],
        ['value' => 'Bar'],
    ])" />
HTML;

    expect($component)->render()
        ->toContain('Foo')
        ->toContain('Bar');
});

it('can render disabled', function () {
    $component = <<<'HTML'
    <x-autocomplete disabled :items="[['value' => 'Foo']]" />
HTML;

    expect($component)->render()
        ->toContain('disabled');
});

it('can render with description and image fields', function () {
    $component = <<<'HTML'
    <x-autocomplete :items="[
        ['value' => 'Alice', 'description' => 'admin', 'image' => '/a.png'],
    ]" />
HTML;

    expect($component)->render()
        ->toContain('Alice');
});

it('can render with remote request', function () {
    $component = <<<'HTML'
    <x-autocomplete request="https://api.example.com/cities" />
HTML;

    expect($component)->render()
        ->toContain('autocomplete');
});

it('throws when items and request are both defined', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Autocomplete: The [items] and [request] cannot be defined at the same time.');

    $component = <<<'HTML'
    <x-autocomplete :items="[['value' => 'Foo']]" request="https://api.example.com/cities" />
HTML;

    expect($component)->render();
});

it('throws when request array misses url', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Autocomplete: The attribute [url] is required in the request array.');

    $component = <<<'HTML'
    <x-autocomplete :request="['method' => 'get']" />
HTML;

    expect($component)->render();
});

it('throws when request method is invalid', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Autocomplete: The attribute [method] must be "get" or "post".');

    $component = <<<'HTML'
    <x-autocomplete :request="['url' => 'https://api.example.com/cities', 'method' => 'put']" />
HTML;

    expect($component)->render();
});

it('throws when request params is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Autocomplete: The attribute [params] must be an array and cannot be empty.');

    $component = <<<'HTML'
    <x-autocomplete :request="['url' => 'https://api.example.com/cities', 'params' => []]" />
HTML;

    expect($component)->render();
});
