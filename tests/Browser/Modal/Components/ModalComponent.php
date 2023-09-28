<?php

namespace Tests\Browser\Modal\Components;

use Livewire\Component;

class ModalComponent extends Component
{
    public bool $modal = false;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-modal wire>
                Foo bar
            </x-modal>
        
            <x-button id="open" wire:click="$toggle('modal')">Open</x-button>
        </div>
HTML;
    }
}
