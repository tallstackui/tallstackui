<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use TallStackUi\Components\Layout\SideBar\Main\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.side-bar.1', [
        'smart' => false,
        'collapsible' => false,
        'thin-scroll' => false,
        'thick-scroll' => false,
        'navigate' => false,
        'navigate-hover' => false,
    ]);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render', function () {
    $component = <<<'HTML'
    <x-side-bar>
        Foo bar
    </x-side-bar>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
});

it('can render smart through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.smart', true);

    __ts_get_component_configuration(Component::class, flush: true);

    Route::get('/reports', fn (): string => Blade::render(<<<'HTML'
    <x-side-bar>
        <x-side-bar.item text="Reports" route="/reports" />
    </x-side-bar>
    HTML))->name('reports');

    $this->get('/reports')
        ->assertOk()
        ->assertSee('aria-current="page"', false);
});

it('can render collapsible through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.collapsible', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar>Foo</x-side-bar>')->render()
        ->toContain("\$store['tsui.side-bar'].collapsible = true");
});

it('can render the thin scroll through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.thin-scroll', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar>Foo</x-side-bar>')->render()
        ->toContain('soft-scrollbar')
        ->not->toContain('custom-scrollbar');
});

it('can render the thick scroll through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.thick-scroll', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar>Foo</x-side-bar>')->render()
        ->toContain('custom-scrollbar')
        ->not->toContain('soft-scrollbar');
});

it('can suppress the global thin scroll through the inline prop', function () {
    config()->set('ts-ui.components.side-bar.1.thin-scroll', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar thick-scroll>Foo</x-side-bar>')->render()
        ->toContain('custom-scrollbar')
        ->not->toContain('soft-scrollbar');
});

it('can render navigate through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.navigate', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar><x-side-bar.item text="Home" /></x-side-bar>')->render()
        ->toContain('wire:navigate')
        ->not->toContain('wire:navigate.hover');
});

it('can render navigate hover through the global configuration', function () {
    config()->set('ts-ui.components.side-bar.1.navigate-hover', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar><x-side-bar.item text="Home" /></x-side-bar>')->render()
        ->toContain('wire:navigate.hover');
});

it('can suppress the global navigate through the inline prop', function () {
    config()->set('ts-ui.components.side-bar.1.navigate', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-side-bar navigate-hover><x-side-bar.item text="Home" /></x-side-bar>')->render()
        ->toContain('wire:navigate.hover');
});
