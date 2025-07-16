<?php

namespace Tests\Browser\Form;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\BrowserTestCase;

class InputTest extends BrowserTestCase
{
    #[Test]
    public function can_clear_input_using_clearable(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $name = 'Jhon Doe';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="name">{{ $name }}</p>
                
                    <x-input dusk="input" wire:model.live="name" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->click('@tallstackui_form_input_clearable')
            ->waitForLivewire()
            ->waitUntilMissingText('Jhon Doe')
            ->assertDontSeeIn('@name', 'Jhon Doe');
    }

    #[Test]
    public function can_preserve_single_zero(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $number = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="number">{{ $number }}</p>
                
                    <x-input dusk="input" wire:model.live="number" :strip-leading-zeros="true" />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '0')
            ->waitForLivewire()
            ->assertSeeIn('@number', '0');
    }

    #[Test]
    public function can_see_clearable(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $name = 'Jhon Doe';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input dusk="input" wire:model="name" clearable />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->assertPresent('@tallstackui_form_input_clearable');
    }

    #[Test]
    public function can_see_validation_error(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $name = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input dusk="input" wire:model="name" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->type('@input', '')
            ->waitForLivewire()->click('@sync')
            ->waitUntilMissingText('Foo bar baz')
            ->assertSee('The name field is required.');
    }

    #[Test]
    public function can_strip_leading_zeros(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $number = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="number">{{ $number }}</p>
                
                    <x-input dusk="input" wire:model.live="number" strip-leading-zeros />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '00123')
            ->waitForLivewire()
            ->assertSeeIn('@number', '123');
    }

    #[Test]
    public function cannot_see_validation_error_when_invalidate(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $name = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input dusk="input" wire:model="name" invalidate />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->type('@input', '')
            ->waitForLivewire()->click('@sync')
            ->waitUntilMissingText('Foo bar baz')
            ->assertDontSee('The name field is required.');
    }

    #[Test]
    public function cannot_see_validation_error_when_invalidate_based_on_config(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Validate('required')]
            public ?string $name = null;

            public function boot(): void
            {
                config(['tallstackui.invalidate_global' => true]);
            }

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input dusk="input" wire:model="name" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                $this->validate();
            }
        })
            ->waitForLivewireToLoad()->type('@input', '')
            ->waitForLivewire()->click('@sync')
            ->waitUntilMissingText('Foo bar baz')
            ->assertDontSee('The name field is required.');
    }
}
