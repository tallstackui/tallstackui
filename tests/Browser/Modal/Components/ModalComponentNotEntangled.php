<?php

namespace Tests\Browser\Modal\Components;

use Livewire\Component;

class ModalComponentNotEntangled extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-modal id="test">
                Foo bar
            </x-modal>
        
            <x-button id="open" x-on:click="$modalOpen('test')">Open</x-button>
        </div>
HTML;
    }
}
