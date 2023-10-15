<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Personalizations\Support\Color;

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
