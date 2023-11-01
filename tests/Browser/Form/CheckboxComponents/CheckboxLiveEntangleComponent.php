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
                <x-radio dusk="entangle-true" wire:model.live="entangle" label="Receive Alert" value="1" />
                <x-radio dusk="entangle-false" wire:model.live="entangle" label="Receive Alert" value="0" />
            </form>
        </div>
HTML;
    }
}
