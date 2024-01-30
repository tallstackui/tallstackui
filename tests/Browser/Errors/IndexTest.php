<?php

namespace Tests\Browser\Errors;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_close(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Rule('required')]
            public ?string $name = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-errors close />
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->validate();
            }
        })
            ->assertSee('Save')
            ->assertDontSee('There are 1 validation errors:')
            ->click('@save')
            ->waitForText('There are 1 validation errors:')
            ->click('@errors-close-button')
            ->waitUntilMissingText('There are 1 validation errors:')
            ->assertDontSee('There are 1 validation errors:');
    }

    /** @test */
    public function can_dispatch_event_when_set(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Rule('required')]
            public ?string $name = null;

            public ?bool $close = false;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-errors close x-on:close="$wire.set('close', 1)" />
                    
                    @if ($close)
                        <p dusk="close">1</p>
                    @endif
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->validate();
            }
        })
            ->assertSee('Save')
            ->assertDontSee('There are 1 validation errors:')
            ->click('@save')
            ->waitForText('There are 1 validation errors:')
            ->click('@errors-close-button')
            ->waitUntilMissingText('There are 1 validation errors:')
            ->assertDontSee('There are 1 validation errors:')
            ->assertVisible('@close');
    }

    /** @test */
    public function can_render(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Rule('required')]
            public ?string $name = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-errors />
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->validate();
            }
        })
            ->assertSee('Save')
            ->assertDontSee('There are 1 validation errors:')
            ->click('@save')
            ->waitForText('There are 1 validation errors:');
    }

    /** @test */
    public function can_render_only_selecteds_fields(): void
    {
        Livewire::visit(new class extends Component
        {
            #[Rule('required')]
            public ?string $description = null;

            #[Rule('required')]
            public ?string $name = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>        
                    <x-errors only="name" />
                
                    <x-button dusk="save" wire:click="save">Save</x-button>
                </div>
                HTML;
            }

            public function save(): void
            {
                $this->validate();
            }
        })
            ->assertSee('Save')
            ->assertDontSee('description')
            ->assertDontSee('name')
            ->click('@save')
            ->waitForText('There are 1 validation errors:')
            ->assertSee('name')
            ->assertDontSee('description');
    }
}
