<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\View\Components\BaseComponent;
use TallStackUi\View\Components\Button\Traits\SetupButton;

#[SoftPersonalization('button')]
class Button extends BaseComponent implements Personalization
{
    use SetupButton;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        #[SkipDebug]
        public ?string $size = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $solid = true,
        public ?bool $outline = false,
        public ?bool $light = false,
        public ?bool $flat = false,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        $this->setup();
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.button.button');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80',
                'sizes' => [
                    'xs' => 'text-xs px-1 py-0.5',
                    'sm' => 'text-sm px-2 py-1',
                    'md' => 'text-md px-4 py-2',
                    'lg' => 'text-lg px-6 py-3',
                ],
            ],
            'icon.sizes' => [
                'xs' => 'w-2 h-2',
                'sm' => 'w-3 h-3',
                'md' => 'w-4 h-4',
                'lg' => 'w-5 h-5',
            ],
        ]);
    }
}
