<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Select\Styled\Component;
use TallStackUi\Components\Spinner\Component as Spinner;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    $this->components = config('ts-ui.components');
});

afterEach(function () {
    config()->set('ts-ui.components', $this->components);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can use as common select', function () {
    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" hint="Foo bar baz" :options="['foo', 'bar', 'baz']" />
HTML;

    expect($component)->render()
        ->toContain('Foo bar baz');
});

it('can use as request select', function () {
    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" hint="Foo bar baz" request="https://foo-bar.com" select="label:label|value:id" />
HTML;

    expect($component)->render()
        ->toContain('Foo bar baz');
});

it('can thrown exception when using options and request', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The [options] and [request] cannot be defined at the same time.');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :options="['foo', 'bar', 'baz']"
                     request="https://foo-bar.com"
    />
HTML;

    expect($component)->render()
        ->toContain('Foo bar baz');
});

it('can thrown exception when request is array using unaceptable method', function (string $method) {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [method] must be "get" or "post".');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :request="[
                        'url' => 'https://foo-bar.com',
                        'method' => '{{ method }}',
                        'params' => [],
                     ]" select="label:label|value:id"
    />
HTML;

    $component = str_replace('{{ method }}', $method, $component);

    expect($component)->render();
})->with(['delete', 'put', 'patch']);

it('can thrown exception when params is empty', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [method] must be "get" or "post".');
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [params] must be an array and cannot be empty.');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :request="[
                        'url' => 'https://foo-bar.com',
                        'method' => 'post',
                        'params' => [],
                     ]" select="label:label|value:id"
    />
HTML;

    expect($component)->render();
});

it('can thrown exception when params is not array', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [params] must be an array and cannot be empty.');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :request="[
                        'url' => 'https://foo-bar.com',
                        'method' => 'post',
                        'params' => 'foo',
                     ]" select="label:label|value:id"
    />
HTML;

    expect($component)->render();
});

it('can render with grouped options', function () {
    $options = [
        [
            'label' => 'Brazil',
            'value' => [
                ['label' => 'São Paulo', 'value' => 4],
                ['label' => 'Rio de Janeiro', 'value' => 5],
            ],
        ],
        [
            'label' => 'United States',
            'value' => [
                ['label' => 'New York', 'value' => 7],
                ['label' => 'Los Angeles', 'value' => 8],
            ],
        ],
    ];

    $html = Blade::render('<x-select.styled label="Cities" :options="$options" select="label:label|value:value" />', ['options' => $options]);

    expect($html)
        ->toContain('Cities')
        ->toContain('option[selectable.value]')
        ->toContain('option[selectable.label]')
        ->not->toContain('option.value');
});

it('can render with selectable keys from the global configuration', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.styled' => [Component::class, ['select' => 'label:name|value:id']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    $options = [['name' => 'New York', 'id' => 1], ['name' => 'Los Angeles', 'id' => 2]];

    expect(Blade::render('<x-select.styled label="Cities" :options="$options" />', compact('options')))
        ->toContain('\\u0022label\\u0022:\\u0022name\\u0022')
        ->toContain('\\u0022value\\u0022:\\u0022id\\u0022');
});

it('can override the global configuration keys inline', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.styled' => [Component::class, ['select' => 'label:name|value:id']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    $options = [['title' => 'New York', 'uuid' => 1]];

    expect(Blade::render('<x-select.styled label="Cities" :options="$options" select="label:title|value:uuid" />', compact('options')))
        ->toContain('\\u0022label\\u0022:\\u0022title\\u0022')
        ->toContain('\\u0022value\\u0022:\\u0022uuid\\u0022')
        ->not->toContain('\\u0022label\\u0022:\\u0022name\\u0022');
});

it('can thrown exception when lazy is less than 10', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [lazy] must be greater than or equal to 10.');

    $component = <<<'HTML'
    <x-select.styled :options="range(1,10000)" :lazy="9" />
HTML;

    expect($component)->render();
});

it('can render readonly as disabled', function () {
    expect('<x-select.styled :options="[1, 2]" readonly />')->render()
        ->toContain('disabled')
        ->toContain('dark:disabled:bg-dark-900');
});

it('can render disabled', function () {
    expect('<x-select.styled :options="[1, 2]" disabled />')->render()
        ->toContain('disabled')
        ->toContain('dark:disabled:bg-dark-900');
});

it('can render the default loading icon', function () {
    expect('<x-select.styled request="https://foo-bar.com" />')->render()
        ->toContain('animate-spin')
        ->not->toContain('dusk="spinner-');
});

it('can render a spinner loading indicator inline', function () {
    expect('<x-select.styled request="https://foo-bar.com" indicator="spinner.bars" />')->render()
        ->toContain('dusk="spinner-bars"');
});

it('can render a spinner loading indicator from the configuration', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.styled' => [Component::class, ['indicator' => 'spinner.dots']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-select.styled request="https://foo-bar.com" />')->render()
        ->toContain('dusk="spinner-dots"');
});

it('can use the global spinner type when the indicator is spinner', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.styled' => [Component::class, ['indicator' => 'spinner']],
    ]);
    config()->set('ts-ui.components.spinner.1.type', 'wave');

    __ts_get_component_configuration(Component::class, flush: true);
    __ts_get_component_configuration(Spinner::class, flush: true);

    try {
        expect('<x-select.styled request="https://foo-bar.com" />')->render()
            ->toContain('dusk="spinner-wave"');
    } finally {
        __ts_get_component_configuration(Spinner::class, flush: true);
    }
});

it('can override the configured spinner indicator inline', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.styled' => [Component::class, ['indicator' => 'spinner.dots']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-select.styled request="https://foo-bar.com" indicator="spinner.bars" />')->render()
        ->toContain('dusk="spinner-bars"')
        ->not->toContain('dusk="spinner-dots"');
});

it('does not render a loading indicator for local options', function () {
    expect('<x-select.styled :options="[\'foo\']" indicator="spinner.bars" />')->render()
        ->not->toContain('dusk="spinner-bars"');
});

it('cannot use an invalid indicator', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [indicator] must be [spinner] or [spinner.{type}]');

    expect('<x-select.styled request="https://foo-bar.com" indicator="spinner.foo" />')->render();
});
