<?php

namespace Tests\Browser\Form\CheckboxComponents;

use Livewire\Component;

class CheckboxLiveEntangleComponent extends Component
{
    public ?bool $entangle = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <p dusk="entangled">@json($entangle)</p>

            <form>
                <x-checkbox dusk="entangle" wire:model.live="entangle" label="Receive Alert" />
            </form>
        </div>
HTML;
    }
}
