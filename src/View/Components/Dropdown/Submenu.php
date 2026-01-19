<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Dropdown\Traits\SharedTransitions;
use TallStackUi\View\Components\Floating;

#[SoftPersonalization('dropdown.submenu')]
class Submenu extends TallStackUiComponent implements Personalization
{
    use SharedTransitions;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?bool $separator = false,
    ) {
        $this->position = $this->position === 'right' ? 'right-start' : 'left-start';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.dropdown.submenu');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'flex items-center gap-1',
            'item' => 'text-secondary-600 dark:hover:bg-dark-600 dark:text-dark-300 dark:focus:bg-dark-600 flex w-full cursor-pointer items-center justify-between whitespace-nowrap px-4 py-2 text-sm transition-colors duration-150 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden',
            'border' => 'dark:border-t-dark-600 border-t border-t-gray-100',
            'icon' => 'h-5 w-5',
            'submenu' => [
                'left' => 'mr-2 h-4 w-4',
                'right' => 'ml-2 h-4 w-4',
            ],
            'floating.default' => collect(app(Floating::class)->personalization())->get('wrapper'),
            'slot' => 'overflow-hidden rounded-md',
        ]);
    }
}
