<?php

namespace Tests\Browser\Errors\Components;

use Livewire\Attributes\Rule;
use Livewire\Component;

class ErrorOnlyComponent extends Component
{
    #[Rule('required')]
    public ?string $name = null;

    #[Rule('required')]
    public ?string $description = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-errors only="name" />
        
            <x-button id="save" wire:click="save">Save</x-button>
        </div>
HTML;
    }

    public function save(): void
    {
        $this->validate();
    }
}
