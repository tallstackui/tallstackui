<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;
use TallStackUi\Support\Personalizations\Traits\InternalColorPersonalizations;

class Badge extends Component implements Personalize
{
    use InternalColorPersonalizations;

    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        public ?bool $square = false,
        public ?bool $round = false,
        public ?string $size = null,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
        $this->position = $this->position === 'right' ? 'right' : 'left';
    }

    public function personalization(): array
    {
        return [
            'wrapper' => Arr::toCssClasses([
                'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
                'text-xs' => $this->size === 'sm',
                'text-sm' => $this->size === 'md',
                'text-md' => $this->size === 'lg',
                'text-white' => $this->color !== 'white' && $this->style === 'solid',
                'rounded-md' => ! $this->round && ! $this->square,
                'rounded-full' => $this->round,
            ]),
            'icon' => Arr::toCssClasses([
                'h-3 w-3',
                'mr-1' => $this->position === 'left',
                'ml-1' => $this->position === 'right',
            ]),
        ];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.badge');
    }
}
