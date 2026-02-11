<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-side-bar>
        Foo bar
    </x-side-bar>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
});
