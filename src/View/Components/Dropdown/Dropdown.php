<?php

namespace TallStackUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('dropdown')]
class Dropdown extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        #[SkipDebug]
        public ?string $header = null,
        #[SkipDebug]
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
                'slot' => 'overflow-hidden rounded-md',
            ],
            'action' => [
                'wrapper' => 'inline-flex w-full gap-x-1.5',
                'text' => 'text-sm text-gray-700 font-medium dark:text-dark-400',
                'icon' => 'h-5 w-5 cursor-pointer text-gray-400 transition',
            ],
        ]);
    }

    /** @throws InvalidSelectedPositionException */
    protected function validate(): void
    {
        InvalidSelectedPositionException::validate(static::class, $this->position);
    }
}
