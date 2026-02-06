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
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?string $color = 'primary',
        public ?bool $invalidate = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->size = $this->sm ? 'sm' : ($this->lg ? 'lg' : 'md');
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
                'disabled' => 'disabled:opacity-50 disabled:cursor-not-allowed',
            ],
        ]);
    }
}
