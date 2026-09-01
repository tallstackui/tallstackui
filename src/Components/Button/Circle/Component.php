<?php

namespace TallStackUi\Components\Button\Circle;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\ButtonSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\CircleButtonColors;
use TallStackUi\Support\Runtime\Components\ButtonRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('button.circle')]
#[ColorsThroughOf(CircleButtonColors::class)]
#[PassThroughRuntime(ButtonRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    use ButtonSetup;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?string $spinner = null,
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?bool $solid = null,
        public ?bool $outline = null,
        public ?bool $light = false,
        public ?bool $flat = false,
        public ?bool $subtle = false,
        public ?bool $tinted = false,
        public ?bool $submit = false,
        public ?bool $unfocus = null,
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
        return view('ts-ui::components.button.circle');
    }

    public function customization(): array
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
                        'lg' => 'size-6 border-[3px]',
                    ],
                ],
                'throbber' => [
                    'base' => 'inline-block animate-spin',
                    'segment' => 'stroke-current',
                    'sizes' => [
                        'xs' => 'size-2',
                        'sm' => 'size-3',
                        'md' => 'size-4',
                        'lg' => 'size-6',
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
                        'lg' => 'size-6',
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
                            'lg' => 'size-6',
                        ],
                        'border' => [
                            'xs' => 'border',
                            'sm' => 'border',
                            'md' => 'border-2',
                            'lg' => 'border-[3px]',
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
                        'lg' => 'size-3',
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
                            'lg' => 'h-6 w-1.5',
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
                            'lg' => 'h-6 w-1',
                        ],
                    ],
                ],
            ],
            'wire' => [
                'loading-cursor' => 'cursor-wait!',
            ],
            'text.sizes' => [
                'xs' => 'text-xs',
                'sm' => 'text-sm',
                'md' => 'text-md',
                'lg' => 'text-lg',
            ],
        ]);
    }

    protected function validate(): void
    {
        $this->guard();
    }
}
