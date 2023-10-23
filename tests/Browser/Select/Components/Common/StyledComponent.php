<?php

namespace Tests\Browser\Select\Components\Common;

use Livewire\Component;

class StyledComponent extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'foo'],
                                ['label' => 'bar', 'value' => 'bar'],
                             ]"
                             select="label:label|value:value"
            />
            
            <x-button dusk="sync" wire:click="sync">Sync</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
