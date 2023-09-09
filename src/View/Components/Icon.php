<?php

namespace TasteUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{
    public function __construct(
        public ?string $icon = null,
        public ?string $solid = null,
        public ?string $style = null,
        public bool $error = false,
    ) {
        $this->style = $this->solid !== null ? 'solid' : 'outline';
    }

    public function render(): View
    {
        return view('taste-ui::components.icon');
    }
}
