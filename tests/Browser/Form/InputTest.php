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

    #[Test]
    public function can_strip_leading_zeros_from_text_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                
                    <x-input dusk="input" type="text" wire:model.live="value" strip-leading-zeros />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '0001')
            ->waitForLivewire()
            ->assertInputValue('@input', '1')
            ->assertSeeIn('@value', '1');
    }

    #[Test]
    public function can_strip_leading_zeros_from_number_input(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                
                    <x-input dusk="input" type="number" wire:model.live="value" strip-leading-zeros />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '0001')
            ->waitForLivewire()
            ->assertInputValue('@input', '1')
            ->assertSeeIn('@value', '1');
    }

    #[Test]
    public function can_preserve_single_zero_with_strip_leading_zeros(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                
                    <x-input dusk="input" type="text" wire:model.live="value" strip-leading-zeros />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '0')
            ->waitForLivewire()
            ->assertInputValue('@input', '0')
            ->assertSeeIn('@value', '0');
    }

    #[Test]
    public function can_handle_decimal_values_with_strip_leading_zeros(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value = '';

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                
                    <x-input dusk="input" type="text" wire:model.live="value" strip-leading-zeros />
                </div>
                HTML;
            }
        })
            ->waitForLivewireToLoad()
            ->type('@input', '0001.50')
            ->waitForLivewire()
            ->assertInputValue('@input', '1.50')
            ->assertSeeIn('@value', '1.50');
    }
}
