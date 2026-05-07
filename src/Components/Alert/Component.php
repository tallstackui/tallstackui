<?php

namespace TallStackUi\Components\Alert;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Colors\Components\AlertColors;
use TallStackUi\TallStackUiComponent;

#[SoftCustomization('alert')]
#[ColorsThroughOf(AlertColors::class)]
class Component extends TallStackUiComponent implements Customization
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?bool $close = false,
        public ?bool $light = false,
        public ?bool $outline = false,
        public ?int $dismiss = null,
        public ?string $rounded = 'lg',
        public ?bool $square = false,
        public ?string $bordered = null,
        #[SkipDebug]
        public ?string $style = 'solid',
        #[SkipDebug]
        public ?string $footer = null,
        #[SkipDebug]
        public array $borderedAttributes = ['side' => null, 'color' => null],
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');

        if ($this->bordered !== null) {
            [$side, $color] = array_pad(explode(':', $this->bordered, 2), 2, null);

            $this->borderedAttributes = [
                'side' => $side,
                'color' => $color !== null && $color !== '' ? $color : $this->color,
            ];
        }
    }

    public function blade(): View
    {
        return view('ts-ui::components.alert.main');
    }

    public function customization(): array
    {
        return Arr::dot([
            'wrapper' => 'p-4',
            'rounded' => [
                'xs' => 'rounded-xs',
                'sm' => 'rounded-sm',
                'md' => 'rounded-md',
                'lg' => 'rounded-lg',
                'xl' => 'rounded-xl',
            ],
            'square' => 'rounded-none',
            'bordered' => [
                'left' => 'border-l-4',
                'right' => 'border-r-4',
            ],
            'content' => [
                'wrapper' => 'flex justify-between flex-wrap',
                'wrapper-with-title' => 'items-start',
                'base' => 'flex-1 flex',
            ],
            'text' => [
                'title' => 'text-lg font-semibold',
                'title-spacing' => 'mb-2',
                'description' => 'text-sm',
            ],
            'close' => [
                'wrapper' => 'ml-auto flex items-start pl-3',
                'size' => 'w-5 h-5',
            ],
            'icon' => [
                'wrapper' => 'mr-2',
                'wrapper-with-title' => 'mt-1',
                'size' => 'w-5 h-5',
            ],
        ]);
    }

    protected function validate(): void
    {
        if ($this->dismiss !== null && $this->dismiss < 1) {
            __ts_validation_exception($this, 'The [dismiss] must be a positive integer.');
        }

        $rounded = ['xs', 'sm', 'md', 'lg', 'xl'];

        if ($this->rounded !== null && ! in_array($this->rounded, $rounded, true)) {
            __ts_validation_exception($this, 'The [rounded] must be one of: ['.implode(', ', $rounded).'].');
        }

        if ($this->bordered === null) {
            return;
        }

        $sides = ['left', 'right'];

        if (! in_array($this->borderedAttributes['side'], $sides, true)) {
            __ts_validation_exception($this, 'The [bordered] side must be one of: ['.implode(', ', $sides).'].');
        }
    }
}
