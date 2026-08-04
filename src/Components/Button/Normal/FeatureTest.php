<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with slot')
    ->expect('<x-button>Foo bar</x-button>')
    ->render()
    ->toContain('Foo bar');

it('can render with text')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render xs')
    ->expect('<x-button text="Foo bar" xs />')
    ->render()
    ->toContain('px-1 py-0.5');

it('can render sm')
    ->expect('<x-button text="Foo bar" sm />')
    ->render()
    ->toContain('px-2 py-1');

it('can render md')
    ->expect('<x-button text="Foo bar" md />')
    ->render()
    ->toContain('px-4 py-2');

it('can render lg')
    ->expect('<x-button text="Foo bar" lg />')
    ->render()
    ->toContain('px-6 py-3');

it('can render square')
    ->expect('<x-button text="Foo bar" square />')
    ->render()
    ->not->toContain('rounded');

it('can render round')
    ->expect('<x-button text="Foo bar" round />')
    ->render()
    ->toContain('rounded-full');

it('can render rounded-md by default')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->toContain('rounded-md');

it('can render round with a size', function (string $round, string $class) {
    $component = <<<HTML
    <x-button text="Foo bar" round="$round" />
    HTML;

    expect($component)->render()
        ->toContain($class);
})->with([
    ['xs', 'rounded-xs'],
    ['sm', 'rounded-sm'],
    ['md', 'rounded-md'],
    ['lg', 'rounded-lg'],
    ['xl', 'rounded-xl'],
    ['full', 'rounded-full'],
]);

it('cannot render round when square is on')
    ->expect('<x-button text="Foo bar" square round="lg" />')
    ->render()
    ->not->toContain('rounded');

it('can thrown exception when round is unnaceptable', function (string $round) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Button\Normal: The [round] must be true or one of: [xs, sm, md, lg, xl, full].');

    $component = <<<HTML
    <x-button text="Foo bar" round="$round" />
    HTML;

    expect($component)->render();
})->with([
    'foo',
    'true',
    '2xl',
    'circle',
]);

it('can render as tag a')
    ->expect('<x-button href="https://google.com.br" text="Foo bar" round />')->render()
    ->toContain('<a  href="https://google.com.br"')
    ->not->toContain('<button');

it('can render as tag a using raw html')
    ->expect('<x-button href="https://google.com.br/?foo=bar&bar=baz" text="Foo bar" round />')->render()
    ->toContain('<a  href="https://google.com.br/?foo=bar&bar=baz"')
    ->not->toContain('<button');

it('can render with icon')
    ->expect('<x-button text="Foo bar" icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render with type submit')
    ->expect('<x-button text="Foo bar" submit />')
    ->render()
    ->toContain('type="submit"', false);

it('can render block')
    ->expect('<x-button text="Foo bar" block />')
    ->render()
    ->toContain('w-full');

it('cannot render block class without block property')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->not->toContain('w-full');

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'bg-white',
        'black' => 'bg-black',
        default => "bg-$colors-500",
    };

    expect($component)->render()
        ->toContain($color);
})->with(colorsDataset());

it('emits data-tsui-unfocus when unfocus is on')
    ->expect('<x-button text="Foo bar" color="primary" unfocus />')
    ->render()
    ->toContain('data-tsui-unfocus');

it('does not emit data-tsui-unfocus by default')
    ->expect('<x-button text="Foo bar" color="primary" />')
    ->render()
    ->not->toContain('data-tsui-unfocus');
