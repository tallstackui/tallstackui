<?php

use Illuminate\View\ViewException;

it('can thrown exception when request was not set', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $this->blade('<x-select.searchable />');
});

it('can thrown exception when select was not set', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $component = <<<'HTML'
    <x-select.searchable label="Options" :request="[
            'url' => 'https://google.com.br',
            'method' => 'delete', 
        ]"
    />
HTML;

    $this->blade($component);
});

it('can thrown exception when request is array using unaceptable method', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [method] must be get or post');

    $component = <<<'HTML'
    <x-select.searchable label="Options" :request="[
            'url' => 'https://google.com.br',
            'method' => 'delete', 
        ]" select="label:label|value:id"
    />
HTML;

    $this->blade($component);
});

it('can thrown exception when request contains an empty array', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [params] must be an array and cannot be empty');

    $component = <<<'HTML'
    <x-select.searchable label="Options" :request="[
            'url' => 'https://google.com.br',
            'method' => 'post', 
            'params' => [],
        ]" select="label:label|value:id"
    />
HTML;

    $this->blade($component);
});
