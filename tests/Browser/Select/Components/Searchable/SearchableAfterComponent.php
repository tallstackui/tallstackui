<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class SearchableAfterComponent extends Component
{
    public ?int $int = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $int }}

            <x-select.searchable wire:model="int"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.simple') }}"
                                 select="label:label|value:value">
                <x-slot:after>
                    Foo Bar
                </x-slot:after>
            </x-select.searchable>
            
            <x-button id="sync" wire:click="sync">Sync</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
