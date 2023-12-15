<?php

namespace Tests\Browser\Form;

use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class PinTest extends BrowserTestCase
{
    /** @test */
    public function can_fill(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $value;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="value">{{ $value }}</p>
                    
                    <x-pin label="Value" wire:model="value" />
                    
                    <x-button dusk="sync" wire:click="sync">Save</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->clickAtXPath('/html/body/div[3]/div/div/input[1]')
            ->keys('input[type="text"]', '1')
            ->click('@sync')
            ->waitForTextIn('@value', '1');

    }

    /** @test */
    public function can_use_prefix(): void
    {
        /** @var Browser $browser */
        $browser = Livewire::visit(new class extends Component
        {
            public ?string $value = "121212";

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <x-pin :prefix="['G-']" label="Value" wire:model="value" numbers />
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        });

        $browser->waitForLivewireToLoad()
            ->pause(1000)
            ->assertSee('G-');
    }
}
