<?php

namespace TasteUi\View\Components\Button;

use Illuminate\Contracts\View\View;

class Circle extends Index
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = null,
        public ?string $solid = null,
        public ?string $outline = null,
        public ?string $color = 'primary',
    ) {
        parent::__construct(text: $text, icon: $icon, solid: $solid, outline: $outline, color: $color);
    }

    public function render(): View
    {
        return view('taste-ui::components.buttons.circle');
    }
}
