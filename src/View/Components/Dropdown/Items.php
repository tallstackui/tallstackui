<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;

class Items extends Component implements Customizable
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $separator = false,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.dropdown.items');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'item' => Arr::toCssClasses([
                'flex cursor-pointer items-center rounded-md px-2 py-2 text-sm transition-colors duration-150 text-secondary-600 hover:bg-gray-100',
                'border-t border-t-gray-100' => $this->separator,
                'gap-x-2' => $this->icon !== null,
            ]),
            'icon' => 'h-5 w-5 text-gray-500',
        ]);
    }
}
