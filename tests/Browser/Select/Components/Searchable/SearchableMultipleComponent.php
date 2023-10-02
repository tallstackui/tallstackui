<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class SearchableMultipleComponent extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            @json($array)

            <x-select.searchable wire:model="array"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.simple') }}"
                                 select="label:label|value:value"
                                 multiple
            />
            
            <x-button id="sync" wire:click="sync">Sync</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
