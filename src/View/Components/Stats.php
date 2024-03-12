<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('stats')]
class Stats extends BaseComponent implements Personalization
{
    public function __construct(
        public string|int|null $number = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?bool $outline = false,
        public ?bool $animated = false,
        public ?bool $increase = false,
        public ?bool $decrease = false,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $right = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.stats');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-700 flex w-full flex-col rounded-lg bg-white shadow-md',
                'second' => 'flex h-full items-center justify-center gap-4',
                'third' => 'flex h-12 w-12 items-center justify-center rounded-lg',
            ],
            'slots' => [
                'header' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                'footer' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                'right' => [
                    'increase' => [
                        'icon' => 'arrow-trending-up',
                        'class' => 'w-6 h-6 text-green-500',
                    ],
                    'decrease' => [
                        'icon' => 'arrow-trending-down',
                        'class' => 'w-6 h-6 text-red-500',
                    ],
                ],
            ],
            'icon' => 'h-8 w-8',
            'title' => 'dark:text-dark-300 text-sm text-gray-600',
            'number' => 'dark:text-dark-300 text-2xl font-bold text-primary-500',
        ]);
    }
}
