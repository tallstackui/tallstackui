<?php

namespace Tests\Browser\Select\Components\Styled;

use Livewire\Component;

class StyledAfterComponent extends Component
{
    public ?string $string = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $string }}

            <x-select.styled wire:model="string"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'bar'],
                                ['label' => 'bar', 'value' => 'foo'],
                             ]"
                             select="label:label|value:value">
                <x-slot:after>
                    Foo Bar
                </x-slot:after>
            </x-select.styled>
            
            <x-button id="sync" wire:click="sync">Sync</x-button>
        </div>
HTML;
    }

    public function sync(): void
    {
        // ...
    }
}
