<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class StyledMultipleLiveEntangleDefaultComponent extends Component
{
    public ?array $array = ['delectus aut autem'];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            @foreach ($array ?? [] as $value)
                <p>{{ $value }}</p>
            @endforeach

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
