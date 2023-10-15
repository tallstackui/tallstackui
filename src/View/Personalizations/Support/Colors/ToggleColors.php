<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Color;
use TallStackUi\View\Components\Form\Toggle;

class ToggleColors
{
    public function __invoke(Toggle $toggle): array
    {
        return [
            'wrapper.color' => TallStackUi::colors()
                ->clean(false)
                ->when($toggle->color === 'white', function (Color $color) {
                    return $color->set('peer-checked:bg', 'gray', 300)
                        ->set('peer-focus:ring', 'gray', 300)
                        ->set('group-focus:ring', 'gray', 300);
                })
                ->unless($toggle->color === 'white', function (Color $color) use ($toggle) {
                    return $color->set('peer-checked:bg', $toggle->color, $toggle->color === 'black' ? null : 600)
                        ->set('peer-focus:ring', $toggle->color, $toggle->color === 'black' ? null : 600)
                        ->set('group-focus:ring', $toggle->color, $toggle->color === 'black' ? null : 600);
                })
                ->get(),
        ];
    }
}
