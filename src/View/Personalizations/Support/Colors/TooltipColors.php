<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Color;
use TallStackUi\View\Components\Tooltip;

class TooltipColors
{
    public function __invoke(Tooltip $tooltip): array
    {
        return [
            'icon.color' => TallStackUi::colors()
                ->when($tooltip->color === 'white', fn (Color $color) => $color->set('text', 'gray', 300))
                ->unless($tooltip->color === 'white', fn (Color $color) => $color->set('text', $tooltip->color, 500))
                ->get(),
        ];
    }
}
