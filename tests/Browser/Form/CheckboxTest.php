<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class CheckboxTest extends BrowserTestCase
{
    /** @test */
    public function can_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?bool $entangle = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="entangled">@json($entangle)</p>

                    <form>
                        <x-checkbox dusk="entangle" wire:model="entangle" label="Receive Alert" />
                    </form>
                    
                    <x-button dusk="sync-entangle" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Receive Alert')
            ->click('@entangle')
            ->click('@sync-entangle')
            ->waitForTextIn('@entangled', 'true')
            ->click('@entangle')
            ->click('@sync-entangle')
            ->waitForTextIn('@entangled', 'false');
    }

    /** @test */
    public function can_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?bool $entangle = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="entangled">@json($entangle)</p>

                    <form>
                        <x-checkbox dusk="entangle" wire:model.live="entangle" label="Receive Alert" />
                    </form>
                </div>
                HTML;
            }
        })
            ->assertSee('Receive Alert')
            ->click('@entangle')
            ->waitForTextIn('@entangled', 'true')
            ->click('@entangle')
            ->waitForTextIn('@entangled', 'false')
            ->click('@entangle')
            ->waitForTextIn('@entangled', 'true')
            ->click('@entangle')
            ->waitForTextIn('@entangled', 'false');
    }
}
