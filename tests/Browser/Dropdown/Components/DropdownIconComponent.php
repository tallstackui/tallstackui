<?php

namespace Tests\Browser\Dropdown\Components;

use Livewire\Component;

class DropdownIconComponent extends Component
{
    public ?string $foo = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-dropdown icon="chevron-down">
                <x-dropdown.items text="Lorem" />
                <x-dropdown.items text="Ipsum" />
            </x-dropdown>
        </div>
HTML;
    }
}
