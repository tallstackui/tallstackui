<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Attributes\SkipDebug;
use TallStackUi\Attributes\SoftPersonalization;
use TallStackUi\Personalization\Contracts\Personalization;
use TallStackUi\Support\Colors\Components\AlertColors;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('alert')]
#[ColorsThroughOf(AlertColors::class)]
class Alert extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?bool $close = false,
        public ?bool $light = false,
        public ?bool $outline = false,
        #[SkipDebug]
        public ?string $style = 'solid',
        #[SkipDebug]
        public ?string $footer = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.alert');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'rounded-lg p-4',
            'content' => [
                'wrapper' => 'flex justify-between flex-wrap',
                'base' => 'flex-1 flex',
            ],
            'text' => [
                'title' => 'text-lg font-semibold',
                'description' => 'text-sm',
            ],
            'close' => [
                'wrapper' => 'ml-auto flex items-start pl-3',
                'size' => 'w-5 h-5',
            ],
            'icon' => [
                'wrapper' => 'mr-2',
                'size' => 'w-5 h-5',
            ],
        ]);
    }
}
