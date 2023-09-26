<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use TasteUi\Contracts\Customizable;
use TasteUi\Facades\TasteUi;

class Tooltip extends Component implements Customizable
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public string $color = 'primary',
        public ?string $sm = null,
        public ?string $md = null,
        public ?string $lg = null,
        public ?bool $solid = null,
        public ?string $size = null,
        public ?string $position = 'top',
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
    }

    public function render(): View
    {
        return view('taste-ui::components.tooltip');
    }

    public function customize(bool $error = false): array
    {
        return [
            'main' => $this->customMainClasses(),
        ];
    }

    public function customMainClasses(): string
    {
        return Arr::toCssClasses([
            'h-5 w-5' => $this->size === 'sm',
            'h-6 w-6' => $this->size === 'md',
            'h-7 w-7' => $this->size === 'lg',
            TasteUi::colors()
                ->set('text', $this->color, 500)
                ->get(),
        ]);
    }
}
