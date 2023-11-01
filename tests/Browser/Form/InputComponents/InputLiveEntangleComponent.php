<?php

namespace Tests\Browser\Form\InputComponents;

use Livewire\Component;

class InputLiveEntangleComponent extends Component
{
    public ?string $entangle = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="entangled">{{ $entangle }}</p>

            <x-input dusk="entangle-live" label="Foo" hint="Bar" wire:model.live="entangle" />
        </div>
HTML;
    }
}
