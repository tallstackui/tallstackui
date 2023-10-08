<?php

namespace Tests\Browser\Tabs\Components;

use Livewire\Component;

class TabsEntangleComponent extends Component
{
    public string $tab = 'Bar';

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-tabs :options="['Foo', 'Bar']" wire:model="tab">
                <x-tabs.items tab="Foo">
                    Foo bar baz
                </x-tabs.items>
                <x-tabs.items tab="Bar">
                    Baz bar foo
                </x-tabs.items>
            </x-tabs>

            <x-button id="change" wire:click="$set('tab', 'Foo')" />
        </div>
HTML;
    }
}
