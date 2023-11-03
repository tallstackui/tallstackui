<?php

namespace Tests\Browser\Form;

use Livewire\Component;
use Livewire\Livewire;
use Tests\Browser\BrowserTestCase;

class InputTest extends BrowserTestCase
{
    /** @test */
    public function can_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $entangle = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="entangled">{{ $entangle }}</p>

                    <x-input dusk="entangle" label="Foo" hint="Bar" wire:model="entangle" />

                    <x-button dusk="sync-entangle" wire:click="sync">Sync</x-button>
                </div>
                HTML;
            }

            public function sync(): void
            {
                //
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->type('@entangle', 'foo-bar-baz')
            ->click('@sync-entangle')
            ->waitForTextIn('@entangled', 'foo-bar-baz')
            ->assertSee('foo-bar-baz');
    }

    /** @test */
    public function can_live_entangle(): void
    {
        Livewire::visit(new class extends Component
        {
            public ?string $entangle = null;

            public function render(): string
            {
                return <<<'HTML'
                <div>
                    <p dusk="entangled">{{ $entangle }}</p>

                    <x-input dusk="entangle-live" label="Foo" hint="Bar" wire:model.live="entangle" />
                </div>
                HTML;
            }
        })
            ->assertSee('Foo')
            ->assertSee('Bar')
            ->typeSlowly('@entangle-live', 'Foo bar baz')
            ->waitForTextIn('@entangled', 'Foo bar baz');
    }
}
