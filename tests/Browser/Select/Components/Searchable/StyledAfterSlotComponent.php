<?php

namespace Tests\Browser\Select\Components\Searchable;

use Livewire\Component;

class StyledAfterSlotComponent extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             :request="route('searchable.simple')"
                             label="Select"
                             hint="Select"
                             select="label:label|value:value">
                <x-slot:after>
                    Ooops!
                </x-slot:after>
            </x-select.styled>
            
            <x-button dusk="sync" wire:click="sync">Sync</x-button>
        </div>
        HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
