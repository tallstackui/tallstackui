<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class StyledMultipleLiveEntangleComponent extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ implode(',', $array ?? []) }}

            <x-select.styled wire:model.live="array"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
                             select="label:label|value:value"
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
