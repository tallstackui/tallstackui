<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;

#[SoftPersonalization('dropdown.items')]
class Items extends Component implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $separator = false,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function personalization(): array
    {
        return [
            'item' => 'text-secondary-600 dark:hover:bg-dark-600 dark:text-dark-300 flex cursor-pointer items-center px-2 py-2 text-sm transition-colors duration-150 hover:bg-gray-100',
            'border' => 'border-t border-t-gray-100 dark:border-t-dark-600',
            'icon' => 'dark:text-dark-300 h-5 w-5 text-gray-500',
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.dropdown.items');
    }
}
