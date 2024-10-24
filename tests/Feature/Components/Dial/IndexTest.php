<?php

it('can render', function () {
    $dial = <<<'HTML'
    <x-dial text="Menu">
        <x-dial.items icon="arrow-up" />
        <x-dial.items icon="arrow-left" />
    </x-dial>
    HTML;

    expect($dial)->render()
        ->toContain('<svg');
});

it('can render with label', function () {
    $dial = <<<HTML
    <x-dial text="Menu">
        <x-dial.items icon="arrow-up" label="Up" />
    </x-dial>
    HTML;

    expect($dial)->render()
        ->toContain('<svg')
        ->toContain('Up');
});