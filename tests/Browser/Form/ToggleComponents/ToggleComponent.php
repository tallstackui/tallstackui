<?php

namespace Tests\Browser\Form\ToggleComponents;

use Livewire\Component;

class ToggleComponent extends Component
{
    public ?bool $entangle = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="entangled">@json($entangle)</p>

            <form>
                <x-toggle dusk="entangle" wire:model="entangle" label="Receive Alert" />
            </form>
            
            <x-button dusk="sync-entangle" wire:click="sync">Sync</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        //
    }
}
