<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

class RadioColors
{
    use DefaultInputClasses;

    public function __invoke(Radio|Checkbox $component): array
    {
        $colors = match ($component->color) {
            'white' => 'gray',
            'black' => 'neutral',
            default => $component->color,
        };

        $weight = match ($component->color) {
            'white' => 300,
            'black' => 700,
            default => 500,
        };

        return [
            'input.color' => TallStackUi::colors()
                ->clean(false)
                ->set('text', $colors, $weight)
                ->set('focus:ring', $colors, $weight)
                ->merge('dark:ring-offset', 'dark', 900)
                ->get(),
        ];
    }
}
