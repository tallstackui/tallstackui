<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('stats')]
class Stats extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $icon = null,
        public ?string $title = null,
        public ?string $color = 'primary',
        public ?int $number = null,
        public ?int $duration = 3,
        public ?string $href = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?string $style = null,
        public ?bool $animated = false,
        public ComponentSlot|string|null $header = null,
        public ComponentSlot|string|null $side = null,
        public ComponentSlot|string|null $footer = null,
    ) {
        $this->style = $this->light ? 'light' : 'solid';
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
                'second' => 'm-2 flex h-full items-center justify-center gap-4',
                'third' => 'flex h-12 w-12 items-center justify-center rounded-lg',
            ],
            'icon' => 'h-6 w-6',
            'title' => 'dark:text-dark-400 text-base font-medium text-gray-500',
            'number' => 'dark:text-dark-200 text-2xl font-bold text-gray-900',
        ]);
    }
}
