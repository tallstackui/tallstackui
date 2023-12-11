<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Support\Personalizations\Contracts\Personalization;
use TallStackUi\Support\Personalizations\SoftPersonalization;

#[SoftPersonalization('alert')]
class Alert extends BaseComponent implements Personalization
{
    //use InteractWithProviders;

    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $color = 'primary',
        public ?bool $close = false,
        public ?bool $light = false,
        public ?bool $outline = false,
        public ?string $style = 'solid',
        public ?string $footer = null,
    ) {
        $this->style = $this->outline ? 'outline' : ($this->light ? 'light' : 'solid');

        //$this->colors();
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
                'wrapper' => 'ml-auto pl-3 flex',
                'size' => 'w-5 h-5',
            ],
            'icon' => [
                'wrapper' => 'mr-2',
                'size' => 'w-5 h-5',
            ],
        ]);
    }
}
