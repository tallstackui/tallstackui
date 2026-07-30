<?php

namespace TallStackUi\Components\Spinner;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\SpinnerColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('spinner')]
#[ColorsThroughOf(SpinnerColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public const FRAMES = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];

    public const SIZES = ['xs', 'sm', 'md', 'lg'];

    // Variants that animate their own text instead of standing next to it.
    public const TEXTUAL = ['shimmer', 'caret', 'terminal', 'thinking'];

    public const TYPES = ['ring', 'throbber', 'gradient', 'ping', 'dots', 'pulse', 'typing', 'bars', 'wave', 'shimmer', 'caret', 'terminal', 'thinking'];

    public function __construct(
        public ?bool $ring = null,
        public ?bool $throbber = null,
        public ?bool $gradient = null,
        public ?bool $ping = null,
        public ?bool $dots = null,
        public ?bool $pulse = null,
        public ?bool $typing = null,
        public ?bool $bars = null,
        public ?bool $wave = null,
        public ?bool $shimmer = null,
        public ?bool $caret = null,
        public ?bool $terminal = null,
        public ?bool $thinking = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public string|bool|null $text = null,
        public ?int $interval = 80,
        #[SkipDebug]
        public ?array $frames = null,
        #[SkipDebug]
        public string|bool|null $label = null,
        #[SkipDebug]
        public ?bool $textual = null,
        #[SkipDebug]
        public ?string $type = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.spinner.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex items-center gap-2',
            'text' => [
                'base' => 'font-medium',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-base',
                    'lg' => 'text-lg',
                ],
            ],
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
                    'xs' => 'size-4 border-2',
                    'sm' => 'size-5 border-2',
                    'md' => 'size-6 border-[3px]',
                    'lg' => 'size-8 border-4',
                ],
            ],
            'throbber' => [
                'base' => 'inline-block animate-spin',
                'segment' => 'stroke-current',
                'sizes' => [
                    'xs' => 'size-4',
                    'sm' => 'size-5',
                    'md' => 'size-6',
                    'lg' => 'size-8',
                ],
            ],
            'gradient' => [
                'base' => 'inline-block animate-spin',
                'track' => 'stroke-current opacity-25',
                'head' => 'fill-current opacity-75',
                'sizes' => [
                    'xs' => 'size-4',
                    'sm' => 'size-5',
                    'md' => 'size-6',
                    'lg' => 'size-8',
                ],
            ],
            'ping' => [
                'wrapper' => 'relative inline-flex',
                'echo' => 'absolute inset-0 animate-ping rounded-full border-current opacity-75',
                'core' => 'relative inline-flex size-full rounded-full border-current',
                'sizes' => [
                    'wrapper' => [
                        'xs' => 'size-4',
                        'sm' => 'size-5',
                        'md' => 'size-6',
                        'lg' => 'size-8',
                    ],
                    'border' => [
                        'xs' => 'border-2',
                        'sm' => 'border-2',
                        'md' => 'border-[3px]',
                        'lg' => 'border-4',
                    ],
                ],
            ],
            'dots' => [
                'wrapper' => 'inline-flex items-center',
                'dot' => 'inline-block animate-spinner-dots rounded-full bg-current',
                'sizes' => [
                    'wrapper' => [
                        'xs' => 'gap-0.5',
                        'sm' => 'gap-1',
                        'md' => 'gap-1',
                        'lg' => 'gap-1.5',
                    ],
                    'dot' => [
                        'xs' => 'size-1',
                        'sm' => 'size-1.5',
                        'md' => 'size-2',
                        'lg' => 'size-2.5',
                    ],
                ],
            ],
            'pulse' => [
                'dot' => 'inline-block animate-spinner-pulse rounded-full bg-current',
                'sizes' => [
                    'xs' => 'size-2',
                    'sm' => 'size-2.5',
                    'md' => 'size-3',
                    'lg' => 'size-4',
                ],
            ],
            'typing' => [
                'wrapper' => 'inline-flex items-center',
                'dot' => 'inline-block animate-spinner-typing rounded-full bg-current',
                'sizes' => [
                    'wrapper' => [
                        'xs' => 'gap-0.5',
                        'sm' => 'gap-1',
                        'md' => 'gap-1',
                        'lg' => 'gap-1.5',
                    ],
                    'dot' => [
                        'xs' => 'size-1',
                        'sm' => 'size-1.5',
                        'md' => 'size-2',
                        'lg' => 'size-2.5',
                    ],
                ],
            ],
            'bars' => [
                'wrapper' => 'inline-flex items-center',
                'bar' => 'inline-block animate-spinner-bars rounded-full bg-current',
                'sizes' => [
                    'wrapper' => [
                        'xs' => 'gap-0.5',
                        'sm' => 'gap-1',
                        'md' => 'gap-1',
                        'lg' => 'gap-1.5',
                    ],
                    'bar' => [
                        'xs' => 'h-3 w-1',
                        'sm' => 'h-4 w-1',
                        'md' => 'h-5 w-1.5',
                        'lg' => 'h-6 w-2',
                    ],
                ],
            ],
            'wave' => [
                'wrapper' => 'inline-flex items-center',
                'bar' => 'inline-block animate-spinner-wave rounded-full bg-current',
                'sizes' => [
                    'wrapper' => [
                        'xs' => 'gap-0.5',
                        'sm' => 'gap-0.5',
                        'md' => 'gap-1',
                        'lg' => 'gap-1',
                    ],
                    'bar' => [
                        'xs' => 'h-3 w-0.5',
                        'sm' => 'h-4 w-1',
                        'md' => 'h-5 w-1',
                        'lg' => 'h-6 w-1.5',
                    ],
                ],
            ],
            'shimmer' => [
                'base' => 'inline-block animate-spinner-shimmer bg-gradient-to-r from-current/30 via-current to-current/30 bg-[length:200%_100%] bg-clip-text font-medium [-webkit-text-fill-color:transparent]',
                'sizes' => [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-base',
                    'lg' => 'text-lg',
                ],
            ],
            'caret' => [
                'wrapper' => 'inline-flex items-center gap-1 font-medium',
                'caret' => 'inline-block animate-spinner-caret bg-current',
                'sizes' => [
                    'text' => [
                        'xs' => 'text-xs',
                        'sm' => 'text-sm',
                        'md' => 'text-base',
                        'lg' => 'text-lg',
                    ],
                    'caret' => [
                        'xs' => 'h-3 w-1',
                        'sm' => 'h-3.5 w-1',
                        'md' => 'h-4 w-1.5',
                        'lg' => 'h-5 w-2',
                    ],
                ],
            ],
            'terminal' => [
                'wrapper' => 'inline-flex items-center gap-1 font-mono',
                'prompt' => 'select-none',
                'caret' => 'inline-block animate-spinner-caret bg-current',
                'sizes' => [
                    'text' => [
                        'xs' => 'text-xs',
                        'sm' => 'text-sm',
                        'md' => 'text-base',
                        'lg' => 'text-lg',
                    ],
                    'caret' => [
                        'xs' => 'h-3 w-1',
                        'sm' => 'h-3.5 w-1',
                        'md' => 'h-4 w-1.5',
                        'lg' => 'h-5 w-2',
                    ],
                ],
            ],
            'thinking' => [
                'wrapper' => 'inline-flex items-center gap-2',
                'glyph' => 'inline-block font-mono leading-none tabular-nums',
                'label' => 'font-medium',
                'sizes' => [
                    'glyph' => [
                        'xs' => 'text-sm',
                        'sm' => 'text-base',
                        'md' => 'text-lg',
                        'lg' => 'text-xl',
                    ],
                    'text' => [
                        'xs' => 'text-xs',
                        'sm' => 'text-sm',
                        'md' => 'text-base',
                        'lg' => 'text-lg',
                    ],
                ],
            ],
        ]);
    }

    protected function setup(): void
    {
        foreach (self::TYPES as $type) {
            if ($this->{$type} === true) {
                $this->type = $type;

                break;
            }
        }

        $this->type ??= __ts_get_component_configuration(self::class, 'type') ?? 'ring';

        $this->size = match (true) {
            $this->lg === true => 'lg',
            $this->md === true => 'md',
            $this->sm === true => 'sm',
            $this->xs === true => 'xs',
            default => __ts_get_component_configuration(self::class, 'size') ?? 'md',
        };

        $this->label = $this->text === true ? null : $this->text;
        $this->label ??= $this->type === 'thinking' ? trans('ts-ui::messages.spinner.thinking') : null;

        $this->textual = in_array($this->type, self::TEXTUAL, true);
        $this->frames = self::FRAMES;
    }

    /** @throws InvalidArgumentException */
    protected function validate(array $data): void
    {
        $flags = array_values(array_filter(self::TYPES, fn (string $type): bool => $this->{$type} === true));

        if (count($flags) > 1) {
            __ts_validation_exception($this, 'Only one variant can be used at a time, but ['.implode(', ', $flags).'] were given');
        }

        if (! in_array($this->type, self::TYPES, true)) {
            __ts_validation_exception($this, 'The [type] must be one of ['.implode(', ', self::TYPES).']');
        }

        if (! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of ['.implode(', ', self::SIZES).']');
        }

        if ($this->interval !== null && $this->interval < 1) {
            __ts_validation_exception($this, 'The [interval] must be greater than 0');
        }

        // Both variants animate the text itself, so there is
        // nothing left on the screen without any content.
        if (in_array($this->type, ['shimmer', 'caret'], true) && blank($this->label) && trim((string) ($data['slot'] ?? '')) === '') {
            __ts_validation_exception($this, "The [{$this->type}] variant animates its own text, so [text] or the default slot is required");
        }
    }
}
