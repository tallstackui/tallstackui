<?php

namespace Tests\Browser\Form\InputComponents;

use Livewire\Component;

class InputComponent extends Component
{
    public ?string $entangle = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="entangled">{{ $entangle }}</p>
       
            <x-input dusk="entangle" label="Foo" hint="Bar" wire:model="entangle" />
            
            <x-button dusk="sync-entangle" wire:click="sync">Save</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        //
    }
}
