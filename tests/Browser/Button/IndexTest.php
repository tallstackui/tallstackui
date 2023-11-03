<?php

namespace Tests\Browser\Button;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class IndexTest extends BrowserTestCase
{
    /** @test */
    public function can_see_loading_spinner_with_circle_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input id="input" wire:model="foo" />
                
                    <x-button.circle dusk="sync" loading="sync" wire:click="sync" text="Save" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                sleep(1);

                // ...
            }
        })
            ->assertDontSee('svg')
            ->type('input', 'Foo bar')
            ->click('@sync')
            ->waitFor('@button-loading-spinner');
    }

    /** @test */
    public function can_see_loading_spinner_with_normal_button(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $foo = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-input id="input" wire:model="foo" />
                
                    <x-button dusk="sync" loading="sync" wire:click="sync" text="Save" />
                </div>
                HTML;
            }

            public function sync(): void
            {
                sleep(1);

                // ...
            }
        })
            ->assertDontSee('svg')
            ->type('input', 'Foo bar')
            ->click('@sync')
            ->waitFor('@button-loading-spinner');
    }
}
