<?php

namespace Tests\Browser\Modal\Components;

use Livewire\Component;

class ModalComponentWithFooter extends Component
{
    public bool $modal = false;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-modal wire title="Bar baz">
                Foo bar
                <x-slot:footer>
                    Lorem                
                </x-slot:footer>
            </x-modal>
        
            <x-button id="open" wire:click="$toggle('modal')">Open</x-button>
        </div>
HTML;
    }
}
