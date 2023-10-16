<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class SearchableMultipleEntangleLiveComponent extends Component
{
    public ?array $array = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            @json($array)

            <x-select.searchable wire:model.live="array"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.simple') }}"
                                 select="label:label|value:value"
                                 multiple
            />
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
