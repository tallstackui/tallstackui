<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class RadioTest extends BrowserTestCase
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
                        <x-radio dusk="entangle-true" wire:model="entangle" label="Receive Alert" value="1" />
                        <x-radio dusk="entangle-false" wire:model="entangle" label="Receive Alert" value="0" />
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
            ->click('@entangle-true')
            ->click('@sync-entangle')
            ->waitForTextIn('@entangled', 'true')
            ->click('@entangle-false')
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
                        <x-radio dusk="entangle-true" wire:model.live="entangle" label="Receive Alert" value="1" />
                        <x-radio dusk="entangle-false" wire:model.live="entangle" label="Receive Alert" value="0" />
                    </form>
                </div>
                HTML;
            }
        })
            ->assertSee('Receive Alert')
            ->click('@entangle-true')
            ->waitForTextIn('@entangled', 'true')
            ->click('@entangle-false')
            ->waitForTextIn('@entangled', 'false');
    }
}
