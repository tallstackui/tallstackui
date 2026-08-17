<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Layout\Header\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

// The component key holds a literal dot, so it cannot be
// reached through the dot notation of the config helper.
function tallstackui_header_size(string $size): void
{
    $components = config('ts-ui.components');

    $components['layout.header'][1]['size'] = $size;

    config()->set('ts-ui.components', $components);

    __ts_get_component_configuration(Component::class, flush: true);
}

afterEach(fn () => tallstackui_header_size('md'));

it('can render', function () {
    $component = <<<'HTML'
    <x-layout.header>
        Foo bar
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
});

it('can render with left slot', function () {
    $component = <<<'HTML'
    <x-layout.header>
        <x-slot:left>
            LeftContent
        </x-slot:left>
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('LeftContent');
});

it('can render with right slot', function () {
    $component = <<<'HTML'
    <x-layout.header>
        <x-slot:right>
            RightContent
        </x-slot:right>
    </x-layout.header>
    HTML;

    expect($component)->render()
        ->toContain('RightContent');
});

it('can render the default height')
    ->expect('<x-layout.header />')
    ->render()
    ->toContain('h-16');

it('can render the height through the size attribute')
    ->expect('<x-layout.header size="lg" />')
    ->render()
    ->toContain('h-20')
    ->not->toContain('h-16');

it('can render the height through the shortcut', function (string $shortcut, string $class) {
    expect('<x-layout.header '.$shortcut.' />')->render()->toContain($class);
})->with([
    ['sm', 'h-14'],
    ['md', 'h-16'],
    ['lg', 'h-20'],
    ['xl', 'h-24'],
]);

it('can render the height through the global configuration', function () {
    tallstackui_header_size('xl');

    expect('<x-layout.header />')->render()
        ->toContain('h-24')
        ->not->toContain('h-16');
});

it('can suppress the global height through the size attribute', function () {
    tallstackui_header_size('xl');

    expect('<x-layout.header size="sm" />')->render()
        ->toContain('h-14')
        ->not->toContain('h-24');
});

it('can suppress the size attribute through the shortcut')
    ->expect('<x-layout.header size="sm" xl />')
    ->render()
    ->toContain('h-24')
    ->not->toContain('h-14');

it('cannot render with an invalid size', function () {
    $this->expectException(ViewException::class);

    expect('<x-layout.header size="2xl" />')->render();
});
