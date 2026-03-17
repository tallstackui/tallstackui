<?php

uses(TestCase::class)->group('Feature');

use Illuminate\View\ViewException;
use Tests\TestCase;

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
        ->toContain('option.value')
        ->toContain('option[selectable.label]');
});

it('can thrown exception when lazy is less than 10', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Select\Styled: The attribute [lazy] must be greater than or equal to 10.');

    $component = <<<'HTML'
    <x-select.styled :options="range(1,10000)" :lazy="9" />
HTML;

    expect($component)->render();
});
