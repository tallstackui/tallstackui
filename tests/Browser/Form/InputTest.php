<?php

namespace Tests\Browser\Form;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class InputTest extends BrowserTestCase
{
    /** @test */
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

    /** @test */
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

    /** @test */
    public function cannot_see_validation_error(): void
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

    /** @test */
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
}
