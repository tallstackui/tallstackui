<?php

namespace Tests\Browser\Modal\Components;

use Livewire\Component;

class ModalComponentWithTitle extends Component
{
    public bool $modal = false;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-modal wire title="Bar baz">
                Foo bar
            </x-modal>
        
            <x-button id="open" wire:click="$toggle('modal')">Open</x-button>
        </div>
HTML;
    }
}
