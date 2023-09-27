<?php

namespace Tests\Browser\Errors\Components;

use Livewire\Attributes\Rule;
use Livewire\Component;

class ErrorComponent extends Component
{
    #[Rule('required')]
    public ?string $name = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-errors />
        
            <x-button id="save" wire:click="save">Save</x-button>
        </div>
HTML;
    }

    public function save(): void
    {
        $this->validate();
    }
}
