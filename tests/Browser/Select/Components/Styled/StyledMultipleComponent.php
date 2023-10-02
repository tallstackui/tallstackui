<?php

namespace Tests\Browser\Select\Components\Styled;

use Livewire\Component;

class StyledMultipleComponent extends Component
{
    public ?array $options = [];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            @json($options)

            <x-select.styled wire:model="options"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'bar'],
                                ['label' => 'bar', 'value' => 'foo'],
                             ]"
                             select="label:label|value:value"
                             searchable
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
