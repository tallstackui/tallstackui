<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-layout>
        Foo bar
    </x-layout>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
});
