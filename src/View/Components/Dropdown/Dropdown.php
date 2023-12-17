<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('dropdown')]
class Dropdown extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $header = null,
        public ?string $action = null,
        public ?string $position = 'bottom-end',
        public ?bool $static = false,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.dropdown.dropdown');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex items-start',
                'second' => 'relative inline-block text-left',
                'third' => 'dark:bg-dark-700 absolute z-10 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5',
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }

    protected function validate(): void
    {
        $positions = [
            'bottom',
            'bottom-start',
            'bottom-end',
            'top',
            'top-start',
            'top-end',
            'left',
            'left-start',
            'left-end',
            'right',
            'right-start',
            'right-end',
        ];

        if (! in_array($this->position, $positions)) {
            throw new InvalidArgumentException('The dropdown position must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
