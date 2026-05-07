<?php

namespace TallStackUi\Components\Badge;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\BadgeColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('badge')]
#[ColorsThroughOf(BadgeColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public ?bool $square = false,
        public bool|string|null $round = false,
        public ?bool $solid = true,
        public ?bool $outline = null,
        public ?bool $light = null,
        #[SkipDebug]
        public ?string $size = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $rounded = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->sm ? 'sm' : 'xs'));
        $this->position = $this->position === 'right' ? 'right' : 'left';
        $this->rounded = $this->round === true ? 'full' : (is_string($this->round) ? $this->round : 'md');
    }

    public function blade(): View
    {
        return view('ts-ui::components.badge.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'outline-hidden inline-flex items-center border px-2 py-0.5 font-bold',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-md',
                    'lg' => 'text-lg',
                ],
            ],
            'clickable' => 'cursor-pointer',
            'icon' => 'h-3 w-3',
            'icon-spacing' => [
                'left' => 'mr-1',
                'right' => 'ml-1',
            ],
            'border.radius' => [
                'xs' => 'rounded-xs',
                'sm' => 'rounded-sm',
                'md' => 'rounded-md',
                'lg' => 'rounded-lg',
                'xl' => 'rounded-xl',
                'full' => 'rounded-full',
            ],
        ]);
    }

    protected function validate(): void
    {
        if (! is_string($this->round)) {
            return;
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl'];

        if (! in_array($this->round, $sizes, true)) {
            __ts_validation_exception($this, 'The [round] must be true or one of: ['.implode(', ', $sizes).'].');
        }
    }
}
