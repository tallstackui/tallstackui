<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-floating>
        Foo bar
    </x-floating>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('x-anchor.bottom-end.offset.10');
});
