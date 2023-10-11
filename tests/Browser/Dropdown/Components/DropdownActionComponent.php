<?php

namespace Tests\Browser\Dropdown\Components;

use Livewire\Component;

class DropdownActionComponent extends Component
{
    public ?string $foo = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-dropdown>
                <x-slot:action>
                    <x-button id="action" x-on:click="show = !show">
                        FooBar                
                    </x-button>
                </x-slot:action>
                <x-dropdown.items text="Lorem" />
                <x-dropdown.items text="Ipsum" />
            </x-dropdown>
        </div>
HTML;
    }
}
