<?php

namespace Tests\Browser\Modal\Components;

use Livewire\Component;

class ModalComponentDifferentEntangle extends Component
{
    public bool $test = false;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-modal wire entangle="test">
                Foo bar
            </x-modal>
        
            <x-button id="open" wire:click="$toggle('test')">Open</x-button>
        </div>
HTML;
    }
}
