<?php

namespace Tests\Browser\Dropdown\Components;

use Livewire\Component;

class DropdownTitleComponent extends Component
{
    public ?string $foo = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-dropdown text="FooBar">
                <x-dropdown.items text="Lorem" />
                <x-dropdown.items text="Ipsum" />
            </x-dropdown>
        </div>
HTML;
    }
}
