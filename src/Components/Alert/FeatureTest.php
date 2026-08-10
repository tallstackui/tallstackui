<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Alert\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render title')
    ->expect('<x-alert title="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render text')
    ->expect('<x-alert text="Bar foo" />')
    ->render()
    ->toContain('Bar foo')
    ->toContain('bg-primary-600');

it('can render slot')
    ->expect('<x-alert>Foo bar</x-alert>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-600');

it('can render with footer slot', function () {
    $component = <<<'HTML'
        <x-alert>
            Foo bar
            <x-slot:footer>
                <button>Button</button>
            </x-slot:footer>
        </x-alert>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Button');
});

it('can render close alert')
    ->expect('<x-alert text="Foo bar" close />')
    ->render()
    ->toContain('<svg class="w-5 h-5 text-primary-50"');

it('can render light')
    ->expect('<x-alert text="Foo bar" light />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-50')
    ->not->toContain('bg-primary-600');

it('can render black background with white text')
    ->expect('<x-alert text="Foo bar" color="black" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-black')
    ->toContain('text-white')
    ->not->toContain('text-black');

it('can render the light style with shadow')
    ->expect('<x-alert title="Foo" text="Bar" light />')
    ->render()
    ->toContain('bg-primary-50 shadow')
    ->not->toContain('shadow-none!');

it('can render shadowless')
    ->expect('<x-alert title="Foo" text="Bar" light shadowless />')
    ->render()
    ->toContain('bg-primary-50')
    ->toContain('shadow-none!');

it('can render shadowless through the global configuration', function () {
    config()->set('ts-ui.components.alert.1.shadowless', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-alert title="Foo" text="Bar" light />')->render()->toContain('shadow-none!');
    } finally {
        config()->set('ts-ui.components.alert.1.shadowless', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the shadowless prop win over the global configuration', function () {
    config()->set('ts-ui.components.alert.1.shadowless', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-alert title="Foo" text="Bar" light :shadowless="false" />')->render()
            ->not->toContain('shadow-none!');
    } finally {
        config()->set('ts-ui.components.alert.1.shadowless', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can render the side border through the global configuration', function () {
    config()->set('ts-ui.components.alert.1.bordered', 'left:red');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-alert title="Foo" text="Bar" light />')->render()
            ->toContain('border-l-4')
            ->toContain('border-red-600');
    } finally {
        config()->set('ts-ui.components.alert.1.bordered', null);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the bordered prop win over the global configuration', function () {
    config()->set('ts-ui.components.alert.1.bordered', 'left:red');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-alert title="Foo" text="Bar" light bordered="right:green" />')->render()
            ->toContain('border-r-4')
            ->toContain('border-green-600')
            ->not->toContain('border-l-4');
    } finally {
        config()->set('ts-ui.components.alert.1.bordered', null);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot use an invalid side coming from the global configuration', function () {
    config()->set('ts-ui.components.alert.1.bordered', 'top');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        $this->expectException(ViewException::class);

        expect('<x-alert title="Foo" text="Bar" light />')->render();
    } finally {
        config()->set('ts-ui.components.alert.1.bordered', null);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});
