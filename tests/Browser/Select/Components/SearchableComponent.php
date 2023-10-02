<?php

namespace Tests\Browser\Select\Components;

use Livewire\Component;

class SearchableComponent extends Component
{
    public ?int $int = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-select.searchable wire:model="int"
                                 label="Select"
                                 hint="Select"
                                 request="{{ route('searchable.simple') }}"
                                 select="label:label|value:id"
            />
        </div>
HTML;
    }
}
