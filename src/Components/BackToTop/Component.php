<?php

namespace TallStackUi\Components\BackToTop;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\BackToTopColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('backToTop')]
#[ColorsThroughOf(BackToTopColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public const POSITIONS = ['bottom-left', 'bottom-right'];

    public const SIZES = ['xs', 'sm', 'md', 'lg'];

    public function __construct(
        public ?string $icon = null,
        public ?string $color = null,
        public ?string $position = null,
        public ?string $anchor = null,
        public ?bool $immediate = null,
        public ?bool $square = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.back-to-top.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'button' => [
                'base' => 'flex items-center justify-center shadow-lg focus:outline-hidden focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-900 cursor-pointer',
                'sizes' => [
                    'xs' => 'h-8 w-8',
                    'sm' => 'h-10 w-10',
                    'md' => 'h-12 w-12',
                    'lg' => 'h-14 w-14',
                ],
            ],
            'icon' => [
                'sizes' => [
                    'xs' => 'h-4 w-4',
                    'sm' => 'h-5 w-5',
                    'md' => 'h-5 w-5',
                    'lg' => 'h-6 w-6',
                ],
            ],
            'shape' => [
                'rounded' => 'rounded-full',
                'square' => 'rounded-lg',
            ],
            'position' => [
                'bottom-left' => 'fixed bottom-6 left-6 z-50',
                'bottom-right' => 'fixed bottom-6 right-6 z-50',
            ],
        ]);
    }

    protected function setup(): void
    {
        $this->icon ??= __ts_get_component_configuration(self::class, 'icon') ?? 'chevron-up';
        $this->color ??= __ts_get_component_configuration(self::class, 'color') ?? 'primary';
        $this->position ??= __ts_get_component_configuration(self::class, 'position') ?? 'bottom-right';
        $this->immediate ??= __ts_get_component_configuration(self::class, 'immediate') ?? false;
        $this->square ??= __ts_get_component_configuration(self::class, 'square') ?? false;

        $this->size = match (true) {
            $this->lg === true => 'lg',
            $this->md === true => 'md',
            $this->sm === true => 'sm',
            $this->xs === true => 'xs',
            default => __ts_get_component_configuration(self::class, 'size') ?? 'md',
        };
    }

    protected function validate(): void
    {
        if (! in_array($this->position, self::POSITIONS, true)) {
            __ts_validation_exception($this, 'The [position] must be one of: '.implode(', ', self::POSITIONS));
        }

        if (! in_array($this->size, self::SIZES, true)) {
            __ts_validation_exception($this, 'The [size] must be one of: '.implode(', ', self::SIZES));
        }
    }
}
