<?php

namespace Tests\Browser\Form\RadioComponents;

use Livewire\Component;

class RadioComponent extends Component
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
}
