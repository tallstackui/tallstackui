<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalPersonalization;

class Circle extends Component implements Customizable
{
    use InternalPersonalization;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';

        $this->validateDelayOptions();
    }

    public function customization(): array
    {
        return [
            ...$this->tallStackUiClasses(),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.buttons.circle');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => Arr::toCssClasses([
                'outline-no ne inline-flex justify-center items-center group transition ease-in duration-150 w-9 h-9 font-semibold',
                'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed rounded-full',
            ]),
            'icon' => [
                'size' => 'w-4 h-4',
                'loading' => 'animate-spin w-4 h-4',
            ],
        ]);
    }
}
