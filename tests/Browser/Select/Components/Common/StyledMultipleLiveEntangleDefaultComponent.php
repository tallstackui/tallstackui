<?php

namespace Tests\Browser\Select\Components\Common;

use Livewire\Component;

class StyledMultipleLiveEntangleDefaultComponent extends Component
{
    public ?array $array = ['foo'];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            @json($array)

            <x-select.styled wire:model.live="array"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'foo'],
                                ['label' => 'bar', 'value' => 'bar'],
                                ['label' => 'baz', 'value' => 'baz'],
                             ]"
                             select="label:label|value:value"
                             searchable
                             multiple
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
