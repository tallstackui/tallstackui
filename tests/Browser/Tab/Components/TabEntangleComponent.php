<?php

namespace Tests\Browser\Tab\Components;

use Livewire\Component;

// TODO: write a test to entangle.live
class TabEntangleComponent extends Component
{
    public string $tab = 'Bar';

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-tab wire:model="tab">
                <x-tab.items tab="Foo">
                    Foo bar baz
                </x-tab.items>
                <x-tab.items tab="Bar">
                    Baz bar foo
                </x-tab.items>
            </x-tab>

            <x-button id="change" wire:click="$set('tab', 'Foo')" text="Click" />
        </div>
HTML;
    }
}
