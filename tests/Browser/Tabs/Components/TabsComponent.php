<?php

namespace Tests\Browser\Tabs\Components;

use Livewire\Component;

class TabsComponent extends Component
{
    public bool $modal = false;

    public function render(): string
    {
        return <<<'HTML'
        <div>        
            <x-tabs :options="['Foo', 'Bar']" selected="Foo">
                <x-tabs.item tab="Foo">
                    Foo bar baz
                </x-tabs.item>
                <x-tabs.item tab="Bar">
                    Baz bar foo
                </x-tabs.item>
            </x-tabs>
        </div>
HTML;
    }
}
