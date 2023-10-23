<?php

use Illuminate\View\ViewException;

it('can use as common select')->todo();

it('can use as request select')->todo();

it('can thrown exception when using options and request')->todo();

it('can thrown exception when select was not defined using select as common')->todo();

it('can thrown exception when select was not defined using select as request')->todo();

it('can thrown exception when request is array using unaceptable method')->todo();

it('can thrown exception when params is empty')->todo();

it('can thrown exception when params is not array')->todo();

it('can thrown exception when options is array of arrays without using select', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [select] parameter must be defined');

    $component = <<<'HTML'
    <x-select.styled :options="[
        ['label' => 'foo', 'value' => 'bar'],
        ['label' => 'baz', 'value' => 'qux'],
    ]" />
HTML;

    $this->blade($component);
});
