<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('dropdown.items')]
class Items extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $separator = false,
    ) {
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.dropdown.items');
    }

    public function personalization(): array
    {
        return [
            'item' => 'text-secondary-600 dark:hover:bg-dark-600 dark:text-dark-300 flex w-full cursor-pointer items-center px-4 py-2 text-sm transition-colors duration-150 hover:bg-gray-100',
            'border' => 'border-t border-t-gray-100 dark:border-t-dark-600',
            'icon' => 'dark:text-dark-300 h-5 w-5 text-gray-500',
        ];
    }
}
