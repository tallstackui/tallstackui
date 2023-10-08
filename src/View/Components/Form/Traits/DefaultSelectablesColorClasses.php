<?php

namespace TallStackUi\View\Components\Form\Traits;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Elements\Color;

trait DefaultSelectablesColorClasses
{
    public function tallStackUiRadioCheckboxColors(): string
    {
        return TallStackUi::colors()
            ->when($this->color === 'white', function (Color $color) {
                return $color->set('text', 'gray', 300)
                    ->set('focus:ring', 'gray', 300);
            })
            ->unless($this->color === 'white', function (Color $color) {
                return $color->set('text', $this->color, $this->color === 'black' ? null : 600)
                    ->set('focus:ring', $this->color, $this->color === 'black' ? null : 600);
            })
            ->get();
    }
}
