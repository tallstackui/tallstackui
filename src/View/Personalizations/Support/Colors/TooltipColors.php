<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Tooltip;

// TODO: refactor
class TooltipColors
{
    public function __construct(protected Tooltip $component)
    {
        //
    }

    public function __invoke(): array
    {
        $colors = match ($this->component->color) {
            'white' => 'gray',
            default => $this->component->color,
        };

        $weight = match ($this->component->color) {
            'white' => 300,
            'black' => null,
            default => 500,
        };

        return ['icon.color' => TallStackUi::tailwind()->set('text', $colors, $weight)->get()];
    }
}
