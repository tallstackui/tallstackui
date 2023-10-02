<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class SearchableFilteredComponent extends Component
{
    public ?int $int = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-select.searchable wire:model="int"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.filtered') }}"
                                 select="label:label|value:value"
            />
        </div>
HTML;
    }
}
