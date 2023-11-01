<?php

namespace Tests\Browser\Form\CheckboxComponents;

use Livewire\Component;

class CheckboxComponent extends Component
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
}
