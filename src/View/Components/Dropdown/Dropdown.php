<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\View\Personalizations\Contracts\Personalize;

class Dropdown extends Component implements Personalize
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $header = null,
        public ?string $action = null,
        public ?bool $right = false,
        public ?bool $static = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-start justify-center',
                'second' => 'relative inline-block text-left',
                'third' => 'dark:bg-dark-700 absolute z-10 mt-2 w-56 bg-white shadow-lg ring-1 ring-black ring-opacity-5',
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }

    public function render(): View
    {
        return view('tallstack-ui::components.dropdown.dropdown');
    }
}
