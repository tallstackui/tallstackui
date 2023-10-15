<?php

namespace Tests\Browser\Tab\Components;

use Livewire\Component;

class TabEntangleLiveComponent extends Component
{
    public string $tab = 'Bar';

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $tab }}

            <x-tab wire:model.live="tab">
                <x-tab.items tab="Foo">
                    Foo bar baz
                </x-tab.items>
                <x-tab.items tab="Bar">
                    Baz bar foo
                </x-tab.items>
            </x-tab>
        </div>
HTML;
    }
}
