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
                ->unless($tooltip->color === 'white', function (Color $color) use ($tooltip) {
                    $colors = match ($tooltip->color) {
                        'black' => 'neutral',
                        default => $tooltip->color,
                    };

                    $weight = match ($tooltip->color) {
                        'black' => 700,
                        default => 500,
                    };

                    return $color->set('text', $colors, $weight);
                })
                ->get(),
        ];
    }
}
