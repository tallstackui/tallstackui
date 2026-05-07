<?php

namespace TallStackUi\Components\Dial\Main;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\DialColors;
use TallStackUi\Support\Runtime\Components\DialRuntime;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('dial')]
#[ColorsThroughOf(DialColors::class)]
#[PassThroughRuntime(DialRuntime::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $icon = null,
        public ?bool $square = false,
        public ?string $position = 'bottom-right',
        public ?string $color = 'primary',
        public ?string $style = 'solid',
        public ?bool $horizontal = false,
        public ?bool $hover = false,
        public ?bool $withoutTooltip = null,
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        #[SkipDebug]
        public ?string $size = null,
    ) {
        $this->icon ??= 'plus';
        $this->size = $this->lg ? 'lg' : ($this->sm ? 'sm' : ($this->xs ? 'xs' : 'md'));
    }

    public function blade(): View
    {
        return view('ts-ui::components.dial.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'icon' => [
                'base' => 'transition-transform duration-200',
                'rotated' => 'rotate-45',
                'sizes' => [
                    'xs' => 'h-4 w-4',
                    'sm' => 'h-5 w-5',
                    'md' => 'h-5 w-5',
                    'lg' => 'h-6 w-6',
                ],
            ],
            'button' => [
                'base' => 'flex items-center justify-center shadow-lg focus:outline-hidden focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-900 cursor-pointer',
                'rounded' => 'rounded-full',
                'sizes' => [
                    'xs' => 'h-8 w-8',
                    'sm' => 'h-10 w-10',
                    'md' => 'h-12 w-12',
                    'lg' => 'h-14 w-14',
                ],
            ],
            'position' => [
                'top-left' => 'fixed top-6 left-6 z-50',
                'top-right' => 'fixed top-6 right-6 z-50',
                'bottom-left' => 'fixed bottom-6 left-6 z-50',
                'bottom-right' => 'fixed bottom-6 right-6 z-50',
            ],
            'items' => 'flex items-center gap-2',
            'items-vertical' => 'flex-col',
        ]);
    }

    protected function validate(): void
    {
        $positions = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];

        if (! in_array($this->position, $positions)) {
            __ts_validation_exception($this, 'The [position] must be one of: '.implode(', ', $positions));
        }
    }
}
