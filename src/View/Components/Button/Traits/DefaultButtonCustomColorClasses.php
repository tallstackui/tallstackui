<?php

namespace TasteUi\View\Components\Button\Traits;

use TasteUi\Facades\TasteUi;
use TasteUi\Support\Elements\Color;

trait DefaultButtonCustomColorClasses
{
    public function customColorClasses(): string
    {
        return TasteUi::colors()
            ->set('ring', $this->color, 500)
            ->when($this->style === 'solid', function (Color $color) {
                return $color->set('bg', $this->color, 500)
                    ->set('hover:bg', $this->color, 600)
                    ->set('hover:ring', $this->color, 600)
                    ->set('text', 'white');
            })
            ->when($this->style === 'outline', function (Color $color) {
                return $color->set('text', $this->color, 500)
                    ->set('border', $this->color, 500)
                    ->set('hover:bg', $this->color, 50)
                    ->set('hover:ring', $this->color, 600)
                    ->append('border');
            })
            ->get();
    }
}
