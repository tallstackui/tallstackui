<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Autocomplete\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    $this->components = config('ts-ui.components');
});

afterEach(function () {
    config()->set('ts-ui.components', $this->components);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render with the default selectable keys', function () {
    expect('<x-autocomplete :items="[[\'value\' => \'São Paulo\']]" />')->render()
        ->toContain('\\u0022value\\u0022:\\u0022value\\u0022')
        ->toContain('\\u0022description\\u0022:\\u0022description\\u0022')
        ->toContain('\\u0022image\\u0022:\\u0022image\\u0022')
        ->toContain('\\u0022metadata\\u0022:\\u0022metadata\\u0022');
});

it('can render remapping the item keys', function () {
    $component = <<<'HTML'
    <x-autocomplete :items="[['name' => 'São Paulo', 'email' => 'sp@example.com']]" select="value:name|description:email|image:avatar" />
    HTML;

    expect($component)->render()
        ->toContain('\\u0022value\\u0022:\\u0022name\\u0022')
        ->toContain('\\u0022description\\u0022:\\u0022email\\u0022')
        ->toContain('\\u0022image\\u0022:\\u0022avatar\\u0022')
        ->toContain('\\u0022metadata\\u0022:\\u0022metadata\\u0022');
});

it('can render remapping the item keys from the global configuration', function () {
    config()->set('ts-ui.components.autocomplete.1.select', 'value:name|description:email');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-autocomplete :items="[[\'name\' => \'São Paulo\']]" />')->render()
        ->toContain('\\u0022value\\u0022:\\u0022name\\u0022')
        ->toContain('\\u0022description\\u0022:\\u0022email\\u0022');
});

it('can override the global configuration keys inline', function () {
    config()->set('ts-ui.components.autocomplete.1.select', 'value:name');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-autocomplete :items="[[\'title\' => \'São Paulo\']]" select="value:title" />')->render()
        ->toContain('\\u0022value\\u0022:\\u0022title\\u0022')
        ->not->toContain('\\u0022value\\u0022:\\u0022name\\u0022');
});

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
        ->toContain('disabled')
        ->toContain('dark:bg-dark-900');
});

it('can render readonly', function () {
    $component = <<<'HTML'
    <x-autocomplete readonly :items="[['value' => 'Foo']]" />
HTML;

    expect($component)->render()
        ->toContain('readonly')
        ->toContain('dark:bg-dark-900');
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

it('forwards the metadata object of local items untouched', function () {
    $component = <<<'HTML'
    <x-autocomplete :items="[
        ['value' => 'Alice', 'metadata' => ['id' => 42, 'role' => 'admin']],
    ]" />
HTML;

    expect($component)->render()
        ->toContain('\u0022metadata\u0022')
        ->toContain('\u0022id\u0022:42')
        ->toContain('\u0022role\u0022:\u0022admin\u0022');
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
