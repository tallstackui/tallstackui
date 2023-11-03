<?php

namespace Tests\Browser\Errors;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
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
                
                    <x-button id="save" wire:click="save">Save</x-button>
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
            ->click('#save')
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
                
                    <x-button id="save" wire:click="save">Save</x-button>
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
            ->click('#save')
            ->waitForText('There are 1 validation errors:')
            ->assertSee('name')
            ->assertDontSee('description');
    }
}
