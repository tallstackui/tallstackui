<?php

use BladeUI\Icons\BladeIconsServiceProvider;
use BladeUI\Icons\Factory;
use Illuminate\View\DynamicComponent;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;

// DynamicComponent keeps a static compiler with the component aliases of the
// first render in the process, so the icons registered here would be unknown.
function forgetDynamicComponentCompiler(): void
{
    Closure::bind(function (): void {
        static::$compiler = null;
        static::$componentClasses = [];
    }, null, DynamicComponent::class)();
}

beforeEach(function () {
    forgetDynamicComponentCompiler();

    $this->original = config('ts-ui.components.icon');

    // Without this, blade-icons registers its own <x-icon> over TallStackUI's.
    config()->set('blade-icons.components.default', 'blade-icon');

    app()->register(BladeIconsServiceProvider::class);
    app()->register(LivewireServiceProvider::class);

    app(Factory::class)->add('tabler', ['path' => __DIR__.'/../../Fixtures/icons', 'prefix' => 'tabler']);
    app(Factory::class)->registerComponents();

    config()->set('ts-ui.components.icon.1.type', 'secondnetwork/blade-tabler-icons');
    config()->set('ts-ui.components.icon.1.custom.guide.arrow-up-tray', 'tabler.upload');

    __ts_get_component_configuration('', flush: true);
});

afterEach(function () {
    config()->set('ts-ui.components.icon', $this->original);

    __ts_get_component_configuration('', flush: true);

    forgetDynamicComponentCompiler();
});

it('can render a blade ui icon')
    ->expect('<x-icon icon="tabler.upload" />')
    ->render()
    ->toContain('M7 9l5-5 5 5');

it('drops null attributes before they reach a blade ui icon')
    ->expect('<x-icon icon="tabler.upload" :x-show="null" />')
    ->render()
    ->toContain('M7 9l5-5 5 5')
    ->not->toContain('x-show');

it('can render the upload with a blade ui icon', function () {
    Livewire::test(new class extends Component
    {
        public $file;

        public function render(): string
        {
            return '<div><x-upload wire:model="file" /></div>';
        }
    })->assertSeeHtml('M7 9l5-5 5 5');
});

it('keeps the upload icon visibility in direct mode with a blade ui icon', function () {
    Livewire::test(new class extends Component
    {
        public $file;

        public function render(): string
        {
            return '<div><x-upload wire:model="file" direct /></div>';
        }
    })->assertSeeHtml('x-show="!uploading"');
});
