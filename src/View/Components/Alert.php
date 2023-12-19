<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('alert')]
class Alert extends BaseComponent implements Personalization
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
