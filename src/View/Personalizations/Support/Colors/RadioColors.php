<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;
use TallStackUi\View\Personalizations\Support\Color;

class RadioColors
{
    use DefaultInputClasses;

    public function __invoke(Radio|Checkbox $component): array
    {
        return [
            'input.color' => TallStackUi::colors()
                ->when($component->color === 'white', function (Color $color) {
                    return $color->set('text', 'gray', 300)
                        ->set('focus:ring', 'gray', 300);
                })
                ->unless($component->color === 'white', function (Color $color) use ($component) {
                    return $color->set('text', $component->color, $component->color === 'black' ? null : 700)
                        ->set('focus:ring', $component->color, $component->color === 'black' ? null : 700);
                })
                ->get(),
        ];
    }
}
