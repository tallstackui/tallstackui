<?php

namespace Tests\Browser\Form\ToggleComponents;

use Livewire\Component;

class ToogleLiveEntangleComponent extends Component
{
    public ?bool $entangle = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="entangled">@json($entangle)</p>

            <form>
                <x-toggle dusk="entangle" wire:model.live="entangle" label="Receive Alert" />
            </form>
        </div>
HTML;
    }
}
