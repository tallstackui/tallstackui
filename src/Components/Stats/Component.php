<?php

namespace TallStackUi\Components\Stats;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\StatsColors;
use TallStackUi\Support\Runtime\Components\StatsRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('stats')]
#[ColorsThroughOf(StatsColors::class)]
#[PassThroughRuntime(StatsRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public string|int|null $number = null,
        public ?string $title = null,
        public ComponentSlot|string|null $icon = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?bool $outline = false,
        public ?bool $animated = false,
        public ?bool $increase = false,
        public ?bool $decrease = false,
        public ?bool $navigate = null,
        public ?bool $navigateHover = null,
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
        return view('ts-ui::components.stats.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'dark:bg-dark-700 flex w-full flex-col rounded-lg bg-white shadow-md',
                'first-clickable' => 'cursor-pointer',
                'second' => 'flex h-full items-center justify-center gap-4',
                'second-no-slot' => 'mx-4',
                'second-no-header' => 'mt-4',
                'second-no-footer' => 'mb-4',
                'third' => 'flex h-12 w-12 items-center justify-center rounded-lg',
            ],
            'slots' => [
                'header' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                'header-string-wrapper' => 'mx-2',
                'footer' => 'dark:text-dark-300 p-2 text-xs text-gray-600',
                'footer-string-wrapper' => 'mx-2',
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
