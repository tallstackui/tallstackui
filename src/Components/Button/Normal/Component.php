<?php

namespace TallStackUi\Components\Button\Normal;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\ButtonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\NormalButtonColors;
use TallStackUi\Support\Runtime\Components\ButtonRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('button')]
#[ColorsThroughOf(NormalButtonColors::class)]
#[PassThroughRuntime(ButtonRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use ButtonSetup;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?bool $block = false,
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $solid = true,
        public ?bool $outline = false,
        public ?bool $light = false,
        public ?bool $flat = false,
        public ?bool $submit = false,
        public ?bool $unfocus = false,
        public ?string $tooltip = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.button.button');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-hidden transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 focus:ring-offset-white enabled:cursor-pointer disabled:cursor-not-allowed disabled:opacity-80',
                'sizes' => [
                    'xs' => 'text-xs px-1 py-0.5',
                    'sm' => 'text-sm px-2 py-1',
                    'md' => 'text-md px-4 py-2',
                    'lg' => 'text-lg px-6 py-3',
                ],
                'border.radius' => [
                    'rounded' => 'rounded-md',
                    'circle' => 'rounded-full',
                ],
                'block' => 'w-full',
            ],
            'wire' => [
                'loading-cursor' => 'cursor-wait!',
            ],
            'icon.sizes' => [
                'xs' => 'w-2 h-2',
                'sm' => 'w-3 h-3',
                'md' => 'w-4 h-4',
                'lg' => 'w-5 h-5',
            ],
            'icon.spinner-animation' => 'animate-spin',
        ]);
    }
}
