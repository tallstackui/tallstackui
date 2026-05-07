<?php

namespace TallStackUi\Components\Progress\Circle;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Traits\ProgressSetup;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\ProgressColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('progress.circle')]
#[ColorsThroughOf(ProgressColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    use ProgressSetup;

    public function __construct(
        public string|int|null $percent = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = true,
        public ?bool $light = false,
        public ?string $color = 'primary',
        public ?int $strokeCircle = 2,
        public ?int $strokePercent = 2,
        public ?int $sizeCircle = 36,
        public ?ComponentSlot $footer = null,
        #[SkipDebug]
        public ?string $style = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        //
    }

    public function blade(): View
    {
        return view('ts-ui::components.progress.circle');
    }

    public function customization(): array
    {
        return Arr::dot([
            'svg' => [
                'stack' => 'h-full w-full col-start-1 row-start-1',
                'stroke' => 'stroke-current',
                'rotation' => 'origin-center -rotate-90 transform',
            ],
            'wrapper' => 'col-start-1 row-start-1 z-10 flex items-center justify-center',
            'text' => 'text-center font-bold text-gray-700 dark:text-dark-400',
            'background' => 'dark:text-dark-700 text-gray-200',
            'sizes' => [
                'text' => [
                    'xs' => 'text-sm',
                    'sm' => 'text-base',
                    'md' => 'text-xl',
                    'lg' => 'text-2xl',
                ],
                'circle' => [
                    'xs' => 'h-12 w-12',
                    'sm' => 'h-16 w-16',
                    'md' => 'h-28 w-28',
                    'lg' => 'h-36 w-36',
                ],
            ],
        ]);
    }
}
