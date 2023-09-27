<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;

class Badge extends Component implements Customizable
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $position = 'right',
        public ?string $md = null,
        public ?string $lg = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
        public ?string $square = null,
        public ?string $size = null,
        public ?string $style = null,
    ) {
        $this->style = $this->outline ? 'outline' : 'solid';
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
        $this->position = $this->position === 'left' ? 'left' : 'right';
    }

    public function render(): View
    {
        return view('taste-ui::components.badge');
    }

    public function customization(bool $error = false): array
    {
        return [
            ...$this->tasteUiMainClasses(),
        ];
    }

    public function tasteUiMainClasses(): array
    {
        return [
            'base' => Arr::toCssClasses([
                'outline-none inline-flex items-center border px-2 py-0.5 font-bold',
                'text-xs' => $this->size === 'sm',
                'text-sm' => $this->size === 'md',
                'text-md' => $this->size === 'lg',
                'text-white' => $this->style === 'solid',
                'rounded-md' => $this->square === null,
                TasteUi::colors()
                    ->set('border', $this->color, 500)
                    ->mergeWhen($this->style === 'solid', 'bg', $this->color, 500)
                    ->get(),
                TasteUi::colors()
                    ->set('text', $this->color, 500)
                    ->get() => $this->style === 'outline',
            ]),
            'icon' => Arr::toCssClasses([
                'h-3 w-3',
                'mr-1' => $this->position === 'left',
                'ml-1' => $this->position === 'right',
                'text-white' => $this->style === 'solid',
                TasteUi::colors()
                    ->set('text', $this->color, 500)
                    ->get() => $this->style === 'outline',
            ]),
        ];
    }
}
