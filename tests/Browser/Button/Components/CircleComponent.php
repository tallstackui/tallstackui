<?php

namespace Tests\Browser\Button\Components;

use Livewire\Component;

class CircleComponent extends Component
{
    public ?string $foo = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-input id="input" wire:model="foo" />
        
            <x-button.circle dusk="sync" loading="sync" wire:click="sync" text="TS" />
        </div>
HTML;
    }

    public function sync(): void
    {
        sleep(1);

        // ...
    }
}
