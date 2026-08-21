<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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

it('can render with email')
    ->expect('<x-input email />')
    ->render()
    ->toContain('<input')
    ->toContain('type="email"');

it('can render with type when email is absent')
    ->expect('<x-input type="number" />')
    ->render()
    ->toContain('type="number"')
    ->not->toContain('type="text"');

it('cannot use email with type', function () {
    $this->expectException(ViewException::class);

    expect('<x-input email type="number" />')->render();
});

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
        ->toContain('flex-none p-1')
        ->toContain('ring-0!')
        ->toContain('focus-within:ring-0!')
        ->toContain('rounded-r-none!')
        ->toContain('[&>button]:rounded-sm!')
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
        ->toContain('flex-none p-1')
        ->toContain('rounded-l-none!')
        ->toContain('[&>button]:rounded-sm!');
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

it('does not render the clearable button while locked', function (string $lock) {
    expect("<x-input name=\"foo\" value=\"bar\" clearable {$lock} />")->render()
        ->not->toContain('tallstackui_form_input_clearable');
})->with(['readonly', 'disabled']);

it('renders the clearable button when unlocked')
    ->expect('<x-input name="foo" value="bar" clearable />')
    ->render()
    ->toContain('tallstackui_form_input_clearable');
