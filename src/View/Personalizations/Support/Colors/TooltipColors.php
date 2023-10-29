<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Personalizations\Support\TailwindClassBuilder;

class TooltipColors
{
    public function __invoke(Tooltip $tooltip): array
    {
        return [
            'icon.color' => TallStackUi::tailwind()
                ->when($tooltip->color === 'white', fn (TailwindClassBuilder $color) => $color->set('text', 'gray', 300))
                ->unless($tooltip->color === 'white', function (TailwindClassBuilder $color) use ($tooltip) {
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
