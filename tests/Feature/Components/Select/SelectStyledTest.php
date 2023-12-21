<?php

use Illuminate\View\ViewException;

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
    $this->expectExceptionMessage('You cannot define [options] and [request] at the same time.');

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

it('can thrown exception when select was not defined using select as common', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :options="[
                        ['label' => 'Foo', 'value' => 'foo'],
                        ['label' => 'Bar', 'value' => 'bar'],
                     ]" />
HTML;

    expect($component)->render()
        ->toContain('Foo bar baz');
});

it('can thrown exception when request is array using unaceptable method', function (string $method) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [method] must be get or post');

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
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [params] must be an array and cannot be empty');

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
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [params] must be an array and cannot be empty');

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

it('can thrown exception when select was not defined using select as request', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     request="https://foo-bar.com" 
    />
HTML;

    expect($component)->render();
});

it('can thrown exception when options is array of arrays without using select', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $component = <<<'HTML'
    <x-select.styled label="Foo bar baz" 
                     hint="Foo bar baz" 
                     :options="[
                        ['label' => 'foo', 'value' => 'bar'],
                        ['label' => 'baz', 'value' => 'qux'],
                     ]" 
    />
HTML;

    expect($component)->render();
});
