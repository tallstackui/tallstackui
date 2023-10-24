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
        
            <x-button dusk="sync" loading="sync" wire:click="sync" text="Save" />
        </div>
HTML;
    }

    public function sync(): void
    {
        sleep(1);

        // ...
    }
}
