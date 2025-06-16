<?php

namespace TallStackUi\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\RangeColors;
use TallStackUi\Foundation\Support\Runtime\Components\RangeRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('form.range')]
#[ColorsThroughOf(RangeColors::class)]
#[PassThroughRuntime(RangeRuntime::class)]
class Range extends TallStackUiComponent implements Personalization
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
        return view('tallstack-ui::components.form.range');
    }

    public function personalization(): array
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
