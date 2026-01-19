<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\ButtonColors;
use TallStackUi\Foundation\Support\Runtime\Components\ButtonRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Button\Traits\Setup;

#[SoftPersonalization('button.circle')]
#[ColorsThroughOf(ButtonColors::class)]
#[PassThroughRuntime(ButtonRuntime::class)]
class Circle extends TallStackUiComponent implements Personalization
{
    use Setup;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?bool $solid = null,
        public ?bool $outline = null,
        public ?bool $light = false,
        public ?bool $flat = false,
        public ?bool $submit = false,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?array $wireable = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.button.circle');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'base' => 'focus:shadow-outline group inline-flex items-center justify-center gap-x-2 rounded-full border text-sm outline-hidden transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 focus:ring-offset-white enabled:cursor-pointer disabled:cursor-not-allowed disabled:opacity-50',
                'sizes' => [
                    'xs' => 'w-4 h-4',
                    'sm' => 'w-6 h-6',
                    'md' => 'w-9 h-9',
                    'lg' => 'w-12 h-12',
                ],
            ],
            'icon.sizes' => [
                'xs' => 'w-2 h-2',
                'sm' => 'w-3 h-3',
                'md' => 'w-4 h-4',
                'lg' => 'w-6 h-6',
            ],
            'text.sizes' => [
                'xs' => 'text-xs',
                'sm' => 'text-sm',
                'md' => 'text-md',
                'lg' => 'text-lg',
            ],
        ]);
    }
}
