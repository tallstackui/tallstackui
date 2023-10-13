<?php

namespace TallStackUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Contracts\Customizable;
use TallStackUi\Support\Personalizations\Traits\InternalPersonalization;

class Index extends Component implements Customizable
{
    use InternalPersonalization;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?string $href = null,
        public ?string $loading = null,
        public ?string $delay = null,
        public ?bool $solid = true,
        public ?bool $outline = false,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));

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
        return view('tallstack-ui::components.buttons.index');
    }

    public function tallStackUiClasses(): array
    {
        return Arr::dot([
            'wrapper' => Arr::toCssClasses([
                'outline-none inline-flex justify-center items-center group ease-in font-semibold transition',
                'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed',
                'gap-x-2' => $this->icon !== null,
                'text-xs px-1 py-0.5' => $this->size === 'xs',
                'text-sm px-2 py-1' => $this->size === 'sm',
                'text-base px-4 py-2' => $this->size === 'md',
                'text-base px-6 py-3' => $this->size === 'lg',
                'rounded' => $this->square === null && $this->round === null,
                'rounded-full' => $this->square === null && $this->round !== null,
            ]),
            'icon' => [
                'size' => Arr::toCssClasses([
                    'w-3 h-3' => $this->size === 'xs' || $this->size === 'sm',
                    'w-4 h-4' => $this->size === 'md',
                    'w-5 h-5' => $this->size === 'lg',
                ]),
                'loading' => Arr::toCssClasses([
                    'animate-spin',
                    'w-3 h-3' => $this->size === 'xs' || $this->size === 'sm',
                    'w-4 h-4' => $this->size === 'md',
                    'w-5 h-5' => $this->size === 'lg',
                ]),
            ],
        ]);
    }
}
