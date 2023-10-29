<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Form\Toggle;

class ToggleColors
{
    public function __invoke(Toggle $toggle): array
    {
        $colors = match ($toggle->color) {
            'white' => 'gray',
            'black' => 'neutral',
            default => $toggle->color,
        };

        $weight = match ($toggle->color) {
            'white' => 300,
            'black' => 700,
            default => 500,
        };

        return [
            'wrapper.color' => TallStackUi::tailwind()
                ->clean(false)
                ->set('peer-checked:bg', $colors, $weight)
                ->set('peer-focus:ring', $colors, $weight)
                ->set('group-focus:ring', $colors, $weight)
                ->merge('dark:ring-offset', 'dark', 900)
                ->get(),
        ];
    }
}
