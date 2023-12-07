<?php

namespace Tests\Browser\Form;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class RangeTest extends BrowserTestCase
{
    /** @test */
    public function can_increase(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-range wire:model="quantity" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        });

        $browser->dragRight('@tallstackui_form_range_input', 20)
            ->click('@sync')
            ->waitForTextIn('@increased', '52')
            ->assertSeeIn('@increased', '52');
    }

    /** @test */
    public function can_increase_with_live_entangle(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            public int $quantity = 0;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="increased">{{ $quantity }}</p>
                    
                    <x-range wire:model.live="quantity" />
                </div>
                HTML;
            }
        });

        $browser->dragRight('@tallstackui_form_range_input', 20)
            ->waitForTextIn('@increased', '52')
            ->assertSeeIn('@increased', '52');
    }
}
