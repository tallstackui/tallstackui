<?php

namespace Tests\Browser\Tab\Components;

use Livewire\Component;

class TabComponent extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-tab selected="Foo">
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
