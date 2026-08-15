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
        public bool|string|null $round = null,
        public ?bool $block = false,
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?string $spinner = null,
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
        public ?string $rounded = null,
        #[SkipDebug]
        public ?string $left = null,
        #[SkipDebug]
        public ?string $right = null,
    ) {
        $this->round ??= __ts_get_component_configuration(self::class, 'round') ?? false;
        $this->rounded = $this->round === true ? 'full' : (is_string($this->round) ? $this->round : 'md');
    }

    public function blade(): View
    {
        return view('ts-ui::components.button.button');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'class' => 'focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-hidden transition-all duration-200 ease-in-out focus:border-transparent focus:ring-2 focus:ring-offset-white enabled:cursor-pointer disabled:cursor-not-allowed disabled:opacity-80',
                'sizes' => [
                    'xs' => 'text-xs px-1 py-0.5',
                    'sm' => 'text-sm px-2 py-1',
                    'md' => 'text-md px-4 py-2',
                    'lg' => 'text-lg px-6 py-3',
                ],
                'block' => 'w-full',
            ],
            'border.radius' => [
                'xs' => 'rounded-xs',
                'sm' => 'rounded-sm',
                'md' => 'rounded-md',
                'lg' => 'rounded-lg',
                'xl' => 'rounded-xl',
                'full' => 'rounded-full',
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
            'spinner' => [
                'delays' => [
                    '',
                    '[animation-delay:120ms]',
                    '[animation-delay:240ms]',
                    '[animation-delay:360ms]',
                    '[animation-delay:480ms]',
                ],
                'ring' => [
                    'base' => 'inline-block animate-spin rounded-full border-current border-t-transparent',
                    'sizes' => [
                        'xs' => 'size-2 border',
                        'sm' => 'size-3 border',
                        'md' => 'size-4 border-2',
                        'lg' => 'size-5 border-2',
                    ],
                ],
                'throbber' => [
                    'base' => 'inline-block animate-spin',
                    'segment' => 'stroke-current',
                    'sizes' => [
                        'xs' => 'size-2',
                        'sm' => 'size-3',
                        'md' => 'size-4',
                        'lg' => 'size-5',
                    ],
                ],
                'gradient' => [
                    'base' => 'inline-block animate-spin',
                    'track' => 'stroke-current opacity-25',
                    'head' => 'fill-current opacity-75',
                    'sizes' => [
                        'xs' => 'size-2',
                        'sm' => 'size-3',
                        'md' => 'size-4',
                        'lg' => 'size-5',
                    ],
                ],
                'ping' => [
                    'wrapper' => 'relative inline-flex',
                    'echo' => 'absolute inset-0 animate-ping rounded-full border-current opacity-75',
                    'core' => 'relative inline-flex size-full rounded-full border-current',
                    'sizes' => [
                        'wrapper' => [
                            'xs' => 'size-2',
                            'sm' => 'size-3',
                            'md' => 'size-4',
                            'lg' => 'size-5',
                        ],
                        'border' => [
                            'xs' => 'border',
                            'sm' => 'border',
                            'md' => 'border-2',
                            'lg' => 'border-2',
                        ],
                    ],
                ],
                'dots' => [
                    'wrapper' => 'inline-flex items-center',
                    'dot' => 'inline-block animate-spinner-dots rounded-full bg-current',
                    'sizes' => [
                        'wrapper' => [
                            'xs' => 'gap-px',
                            'sm' => 'gap-0.5',
                            'md' => 'gap-0.5',
                            'lg' => 'gap-1',
                        ],
                        'dot' => [
                            'xs' => 'size-0.5',
                            'sm' => 'size-1',
                            'md' => 'size-1',
                            'lg' => 'size-1.5',
                        ],
                    ],
                ],
                'pulse' => [
                    'dot' => 'inline-block animate-spinner-pulse rounded-full bg-current',
                    'sizes' => [
                        'xs' => 'size-1',
                        'sm' => 'size-1.5',
                        'md' => 'size-2',
                        'lg' => 'size-2.5',
                    ],
                ],
                'typing' => [
                    'wrapper' => 'inline-flex items-center',
                    'dot' => 'inline-block animate-spinner-typing rounded-full bg-current',
                    'sizes' => [
                        'wrapper' => [
                            'xs' => 'gap-px',
                            'sm' => 'gap-0.5',
                            'md' => 'gap-0.5',
                            'lg' => 'gap-1',
                        ],
                        'dot' => [
                            'xs' => 'size-0.5',
                            'sm' => 'size-1',
                            'md' => 'size-1',
                            'lg' => 'size-1.5',
                        ],
                    ],
                ],
                'bars' => [
                    'wrapper' => 'inline-flex items-center',
                    'bar' => 'inline-block animate-spinner-bars rounded-full bg-current',
                    'sizes' => [
                        'wrapper' => [
                            'xs' => 'gap-px',
                            'sm' => 'gap-0.5',
                            'md' => 'gap-0.5',
                            'lg' => 'gap-1',
                        ],
                        'bar' => [
                            'xs' => 'h-2 w-0.5',
                            'sm' => 'h-3 w-0.5',
                            'md' => 'h-4 w-1',
                            'lg' => 'h-5 w-1',
                        ],
                    ],
                ],
                'wave' => [
                    'wrapper' => 'inline-flex items-center',
                    'bar' => 'inline-block animate-spinner-wave rounded-full bg-current',
                    'sizes' => [
                        'wrapper' => [
                            'xs' => 'gap-px',
                            'sm' => 'gap-px',
                            'md' => 'gap-px',
                            'lg' => 'gap-0.5',
                        ],
                        'bar' => [
                            'xs' => 'h-2 w-px',
                            'sm' => 'h-3 w-0.5',
                            'md' => 'h-4 w-0.5',
                            'lg' => 'h-5 w-0.5',
                        ],
                    ],
                ],
            ],
        ]);
    }

    protected function validate(): void
    {
        $this->guard();

        if (! is_string($this->round)) {
            return;
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl', 'full'];

        if (! in_array($this->round, $sizes, true)) {
            __ts_validation_exception($this, 'The [round] must be true or one of: ['.implode(', ', $sizes).'].');
        }
    }
}
