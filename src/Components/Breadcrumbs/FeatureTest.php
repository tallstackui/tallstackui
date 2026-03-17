<?php

use TallStackUi\Components\Breadcrumbs\Component;
use TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with items', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Users', 'link' => '/users'],
        ['label' => 'John'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('Home')
        ->toContain('Users')
        ->toContain('John')
        ->toContain('<nav');
});

it('can render item links as anchor tags', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Users', 'link' => '/users'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('<a')
        ->toContain('href="/"')
        ->toContain('href="/users"');
});

it('can render current item without link as span', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Current Page'],
    ]" />
    HTML;

    $rendered = expect($component)->render();
    $rendered->toContain('Current Page');
    $rendered->toContain('font-medium');
});

it('can render default separator', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('/')
        ->toContain('select-none');
});

it('can render custom text separator', function () {
    $component = <<<'HTML'
    <x-breadcrumbs separator="»" :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('»');
});

it('can render separator class', function () {
    $component = <<<'HTML'
    <x-breadcrumbs separator-class="text-red-500" :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('text-red-500');
});

it('can render item with tooltip', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/', 'tooltip' => 'Go home'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('x-tooltip="Go home"')
        ->toContain('x-data');
});

it('can render left slot', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[['label' => 'Home', 'link' => '/']]">
        <x-slot:left>
            <span id="left-content">Left</span>
        </x-slot:left>
    </x-breadcrumbs>
    HTML;

    expect($component)->render()
        ->toContain('id="left-content"')
        ->toContain('Left');
});

it('can render right slot', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[['label' => 'Home', 'link' => '/']]">
        <x-slot:right>
            <span id="right-content">Right</span>
        </x-slot:right>
    </x-breadcrumbs>
    HTML;

    expect($component)->render()
        ->toContain('id="right-content"')
        ->toContain('Right');
});

it('can render dark mode classes', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('dark:text-dark-300')
        ->toContain('dark:text-dark-200');
});

it('can render with collection', function () {
    $items = collect([
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Users', 'link' => '/users'],
    ]);

    $rendered = Blade::render('<x-breadcrumbs :items="$items" />', ['items' => $items]);

    expect($rendered)
        ->toContain('Home')
        ->toContain('Users');
});

it('can render aria label', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[['label' => 'Home', 'link' => '/']]" />
    HTML;

    expect($component)->render()
        ->toContain('aria-label="Breadcrumb"');
});

it('renders nothing when items is empty', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[]" />
    HTML;

    expect($component)->render()
        ->not->toContain('<nav');
});

it('does not render separator before first item', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[['label' => 'Home', 'link' => '/']]" />
    HTML;

    expect($component)->render()
        ->not->toContain('select-none');
});

it('can render default md size', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('text-sm')
        ->toContain('gap-1');
});

it('can render xs size', function () {
    $component = <<<'HTML'
    <x-breadcrumbs xs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('text-xs')
        ->toContain('gap-0.5');
});

it('can render sm size', function () {
    $component = <<<'HTML'
    <x-breadcrumbs sm :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('text-xs')
        ->toContain('gap-0.5');
});

it('can render lg size', function () {
    $component = <<<'HTML'
    <x-breadcrumbs lg :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Page'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('text-base')
        ->toContain('gap-1.5');
});

it('can resolve named route in link', function () {
    Route::get('/test-breadcrumb-route', fn () => '')->name('test.breadcrumb');

    $rendered = Blade::render('<x-breadcrumbs :items="$items" />', [
        'items' => [
            ['label' => 'Test', 'link' => 'test.breadcrumb'],
        ],
    ]);

    expect($rendered)
        ->toContain('/test-breadcrumb-route');
});

it('can load breadcrumb definitions from file paths', function () {
    $tmp = tempnam(sys_get_temp_dir(), 'bc_').'.php';

    file_put_contents($tmp, '<?php
        use TallStackUi\Facades\TallStackUi;
        use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

        TallStackUi::breadcrumbs()
            ->for("file.loaded.home", fn (BreadcrumbTrail $trail) => $trail->add("Home", "/"));
    ');

    config()->set('ts-ui.components.breadcrumbs', [
        Component::class,
        ['files' => [$tmp]],
    ]);

    __ts_get_component_configuration('', flush: true);

    require $tmp;

    $registry = app(BreadcrumbRegistry::class);

    expect($registry->has('file.loaded.home'))->toBeTrue();

    @unlink($tmp);
});

it('keeps regular urls unchanged', function () {
    $component = <<<'HTML'
    <x-breadcrumbs :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'External', 'link' => 'https://example.com'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('href="/"')
        ->toContain('href="https://example.com"');
});
