<?php

namespace TallStackUi\Components\Form\Range;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\RangeColors;
use TallStackUi\Support\Runtime\Components\RangeRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('form.range')]
#[ColorsThroughOf(RangeColors::class)]
#[PassThroughRuntime(RangeRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ComponentSlot|string|null $label = null,
        public ComponentSlot|string|null $hint = null,
        public ?bool $dual = null,
        public int|float|null $min = null,
        public int|float|null $max = null,
        public int|float|null $step = null,
        public array|int|float|string|null $value = null,
        public ?bool $tooltip = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');

        if ($this->dual) {
            $this->min ??= 0;
            $this->max ??= 100;
            $this->step ??= 1;
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.form.range');
    }

    public function customization(): array
    {
        return Arr::dot([
            'input' => [
                'wrapper' => 'relative rounded-md',
                'base' => 'dark:bg-dark-800 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 transition',
                'sizes' => [
                    'sm' => 'h-1 [&::-webkit-slider-thumb]:h-3 [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                    'md' => 'h-2 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                    'lg' => 'h-3 [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full',
                ],
                'locked' => 'cursor-not-allowed! opacity-50',
            ],
            'dual' => [
                'wrapper' => [
                    'base' => 'relative w-full',
                    'sizes' => [
                        'sm' => 'h-3',
                        'md' => 'h-4',
                        'lg' => 'h-6',
                    ],
                    'locked' => 'cursor-not-allowed! opacity-50',
                ],
                'track' => [
                    'base' => 'dark:bg-dark-800 absolute inset-x-0 top-1/2 -translate-y-1/2 rounded-lg bg-gray-200',
                    'progress' => 'absolute top-1/2 -translate-y-1/2 rounded-lg',
                    'sizes' => [
                        'sm' => 'h-1',
                        'md' => 'h-2',
                        'lg' => 'h-3',
                    ],
                ],
                'input' => [
                    'base' => 'pointer-events-none absolute inset-x-0 top-1/2 m-0 w-full -translate-y-1/2 cursor-pointer appearance-none bg-transparent p-0 [&::-moz-range-progress]:bg-transparent [&::-moz-range-track]:bg-transparent',
                    'sizes' => [
                        'sm' => 'h-3 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-3 [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-3 [&::-moz-range-thumb]:w-3 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0',
                        'md' => 'h-4 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0',
                        'lg' => 'h-6 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-6 [&::-moz-range-thumb]:w-6 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0',
                    ],
                ],
                'tooltip' => [
                    'anchor' => 'pointer-events-none absolute inset-y-0 w-0',
                ],
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->dual) {
            return;
        }

        if ($this->tooltip) {
            __ts_validation_exception($this, 'The [tooltip] can only be used along with [dual].');
        }

        if (is_array($this->value)) {
            __ts_validation_exception($this, 'The [value] can only be an array along with [dual].');
        }
    }
}
