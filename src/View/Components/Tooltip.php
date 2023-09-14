<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tooltip extends Component
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
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : 'sm');
    }

    public function render(): View
    {
        return view('taste-ui::components.tooltip');
    }
}
