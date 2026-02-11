<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-input />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-input label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-input label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with icon', function (string $position) {
    $component = <<<HTML
    <x-input label="Foo bar" hint="Bar baz" icon="cog" position="$position" />
    HTML;

    $position = match ($position) {
        'left' => 'pl-8',
        'right' => 'pr-8',
    };

    expect($component)->render()
        ->toContain('<input')
        ->toContain('Bar baz')
        ->toContain('Foo bar')
        ->toContain('<svg')
        ->toContain($position);
})->with(['left', 'right']);

it('can render with strip-zeros')
    ->expect('<x-input strip-zeros />')
    ->render()
    ->toContain('<input')
    ->toContain('x-data="tallstackui_formInputStripZeros');

it('can render with strip-zeros and type number')
    ->expect('<x-input type="number" strip-zeros />')
    ->render()
    ->toContain('<input')
    ->toContain('type="number"')
    ->toContain('x-data="tallstackui_formInputStripZeros');

it('can render with strip-zeros and initial value')
    ->expect('<x-input strip-zeros value="0001" />')
    ->render()
    ->toContain('<input')
    ->toContain('value="0001"')
    ->toContain('x-data="tallstackui_formInputStripZeros');

it('can render with suffix button addon', function () {
    $component = <<<'HTML'
    <x-input label="Search">
        <x-slot:suffix button>
            <x-button text="Go" sm />
        </x-slot:suffix>
    </x-input>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('Go')
        ->toContain('flex-none')
        ->toContain('ring-0!')
        ->toContain('focus-within:ring-0!')
        ->toContain('rounded-l-none!')
        ->toContain('focus-within:ring-primary-600');
});

it('can render with prefix button addon', function () {
    $component = <<<'HTML'
    <x-input label="URL">
        <x-slot:prefix button>
            <x-button text="https" sm />
        </x-slot:prefix>
    </x-input>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('https')
        ->toContain('flex-none')
        ->toContain('rounded-r-none!')
        ->toContain('[&>button]:rounded-r-none!');
});

it('can render with buttons on both sides', function () {
    $component = <<<'HTML'
    <x-input label="Amount">
        <x-slot:prefix button>
            <x-button text="-" sm />
        </x-slot:prefix>
        <x-slot:suffix button>
            <x-button text="+" sm />
        </x-slot:suffix>
    </x-input>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('rounded-l-none!')
        ->toContain('rounded-r-none!');
});

it('does not render addon wrapper for string prefix')
    ->expect('<x-input prefix="$" />')
    ->render()
    ->toContain('<input')
    ->not->toContain('flex-none');

it('does not render addon wrapper for slot prefix without button attribute', function () {
    $component = <<<'HTML'
    <x-input>
        <x-slot:prefix>$</x-slot:prefix>
    </x-input>
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->not->toContain('flex-none');
});
