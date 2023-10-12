<?php

namespace Tests\Browser\Select\Components\Styled;

use Livewire\Component;

class StyledMultipleLiveEntangleWithDefaultComponent extends Component
{
    public ?array $options = ['bar', 'foo'];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            @json($options)

            <x-select.styled wire:model.live="options"
                             label="Select"
                             hint="Select"
                             :options="[
                                ['label' => 'foo', 'value' => 'bar'],
                                ['label' => 'bar', 'value' => 'foo'],
                                ['label' => 'baz', 'value' => 'baz'],
                             ]"
                             select="label:label|value:value"
                             searchable
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
