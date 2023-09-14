<?php

namespace TasteUi\View\Components\Button;

use Closure;

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

    public function render(): Closure
    {
        return function (array $data) {
            return view('taste-ui::components.buttons.circle', $this->merge($data))->render();
        };
    }
}
