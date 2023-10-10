<?php

namespace Tests\Browser\Button\Components;

use Livewire\Component;

class ButtonComponent extends Component
{
    public ?string $foo = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-input id="input" wire:model="foo" />
        
            <x-button id="delay" loading="short" wire:click="short" text="Save" />
        </div>
HTML;
    }

    public function short(): void
    {
        sleep(2);

        // ...
    }
}
