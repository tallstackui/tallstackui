<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalPersonalization;

class Alert extends Component implements Customizable
{
    use InternalPersonalization;

    public function __construct(
        public ?string $title = null,
        public ?string $text = null,
        public ?string $icon = null,
        public string $color = 'primary',
        public bool $closeable = false,
        public bool $translucent = false,
        public bool $pulse = false,
        public string $style = 'solid',
    ) {
        $this->style = $this->translucent && $this->color !== 'white' ? 'translucent' : 'solid';
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.alert');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => Arr::toCssClasses([
                'rounded-md p-4',
                'animate-pulse' => $this->pulse,
            ]),
            'title' => [
                'base' => 'text-lg font-semibold',
                'wrapper' => 'flex items-center justify-between',
                'icon' => [
                    'wrapper' => 'ml-auto pl-3',
                    'size' => 'w-5 h-5',
                ],
            ],
            'text' => [
                'wrapper' => 'flex items-center justify-between',
                'title' => [
                    'wrapper' => Arr::toCssClasses([
                        'text-sm',
                        'inline-flex' => $this->title === null && $this->icon !== null,
                        'mt-2' => $this->title !== null,
                    ]),
                    'icon' => [
                        'wrapper' => 'flex items-center',
                        'size' => 'w-5 h-5',
                    ],
                ],
            ],
            'icon' => [
                'wrapper' => 'mr-2',
                'size' => 'w-5 h-5',
            ],
        ]);
    }
}
