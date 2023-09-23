<?php

namespace TasteUi\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Facades\TasteUi;
use TasteUi\Support\Elements\Color;

class Index extends Component
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'left',
        public ?string $xs = null,
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $size = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $round = null,
        public ?string $href = null,
        private ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->xs ? 'xs' : ($this->sm ? 'sm' : ($this->lg ? 'lg' : 'md'));
    }

    public function render(): View
    {
        return view('taste-ui::components.buttons.index');
    }

    public function baseClass(): string
    {
        //TODO: black and white buttons
        //TODO: hover in outline buttons
        return Arr::toCssClasses([
            'outline-none inline-flex justify-center items-center group ease-in font-semibold transition',
            'focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed',
            'gap-x-2' => $this->icon !== null,
            'text-xs px-1 py-0.5' => $this->size === 'xs',
            'text-sm px-2 py-1' => $this->size === 'sm',
            'text-base px-4 py-2' => $this->size === 'md',
            'text-base px-6 py-3' => $this->size === 'lg',
            'rounded' => $this->square === null && $this->round === null,
            'rounded-full' => $this->square === null && $this->round !== null,
            TasteUi::colors()
                ->set('ring', $this->color, 500)
                ->when($this->style === 'solid', function (Color $color) {
                    return $color->set('bg', $this->color, 500)
                        ->set('hover:bg', $this->color, 600)
                        ->set('hover:ring', $this->color, 600)
                        ->set('text', 'white');
                })
                ->when($this->style === 'outline', function (Color $color) {
                    return $color->set('text', $this->color, 500)
                        ->set('border', $this->color, 500)
                        ->set('hover:bg', $this->color, 50)
                        ->set('hover:ring', $this->color, 600)
                        ->append('border');
                })
                ->get(),
        ]);
    }

    public function iconClass(): string
    {
        return Arr::toCssClasses([
            'w-2 h-2' => $this->size === 'xs' || $this->size === 'sm',
            'w-3 h-3' => $this->size === 'md',
            'w-5 h-5' => $this->size === 'lg',
            TasteUi::colors()
                ->when($this->style === 'solid', fn (Color $color) => $color->set('text', 'white'))
                ->when($this->style === 'outline', fn (Color $color) => $color->set('text', $this->color, 500))
                ->get(),
        ]);
    }
}
