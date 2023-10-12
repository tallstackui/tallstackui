<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class SearchableLoadLiveEntangleComponent extends Component
{
    public ?int $int = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $int }}

            <x-select wire:model.live="int" :options="[1,2,3]" />

            <x-select.searchable wire:model.live="int"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.simple') }}"
                                 select="label:label|value:value"
            />
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
