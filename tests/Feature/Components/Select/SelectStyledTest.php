<?php

use Illuminate\View\ViewException;

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
