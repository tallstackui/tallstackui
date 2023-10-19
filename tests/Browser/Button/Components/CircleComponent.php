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
        
            <x-button.circle id="delay" loading="short" wire:click="short" text="TS" />
        </div>
HTML;
    }

    public function short(): void
    {
        sleep(2);

        // ...
    }
}
