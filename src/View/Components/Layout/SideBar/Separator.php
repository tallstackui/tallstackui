<?php

namespace TallStackUi\View\Components\Layout\SideBar;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('sideBar.separator')]
class Separator extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?bool $simple = null,
        public ?bool $line = null,
        public ?bool $lineRight = null,
    ) {
        match (true) {
            $this->simple => $this->line = false,
            $this->line => $this->simple = false,
            $this->lineRight => $this->lineRight = true,
            default => $this->simple = true,
        };
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.layout.sidebar.separator');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'simple' => [
                'wrapper' => 'flex py-2 pl-2',
                'base' => 'text-primary-600 dark:text-dark-100 text-base font-semibold leading-6',
            ],
            'line' => [
                'wrapper' => [
                    'first' => 'relative',
                    'second' => 'absolute inset-0 flex items-center',
                    'third' => 'relative flex justify-center',
                ],
                'border' => 'border-primary-100 dark:border-dark-500 w-full border-t',
                'base' => 'dark:bg-dark-700 text-primary-600 dark:text-dark-100 bg-white px-3 text-base font-semibold',
            ],
            'line-right' => [
                'wrapper' => [
                    'first' => 'relative',
                    'second' => 'absolute inset-0 flex items-center',
                    'third' => 'relative flex justify-start',
                ],
                'border' => 'border-primary-100 dark:border-dark-500 w-full border-t',
                'base' => 'dark:bg-dark-700 text-primary-600 dark:text-dark-100 bg-white px-3 text-base font-semibold',
            ],
        ]);
    }
}
