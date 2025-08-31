<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Runtime\Components\DropdownItemsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('dropdown.items')]
#[PassThroughRuntime(DropdownItemsRuntime::class)]
class Items extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $href = null,
        public ?bool $separator = false,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
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
            'item' => 'text-secondary-600 dark:text-dark-300 dark:hover:bg-dark-600 dark:focus:bg-dark-600 flex w-full cursor-pointer items-center whitespace-nowrap px-4 py-2 text-sm transition-colors duration-150 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden',
            'border' => 'dark:border-t-dark-600 border-t border-t-gray-100',
            'icon' => 'dark:text-dark-300 h-5 w-5 text-gray-500',
        ];
    }
}
