<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

// TODO: refactor
class RadioColors
{
    use DefaultInputClasses;

    public function __construct(protected Radio|Checkbox $component)
    {
        //
    }

    public function __invoke(): array
    {
        $colors = match ($this->component->color) {
            'white' => 'gray',
            default => $this->component->color,
        };

        $weight = match ($this->component->color) {
            'white' => 300,
            'black' => null,
            default => 500,
        };

        return [
            'input.color' => TallStackUi::tailwind()
                ->clean(false)
                ->set('text', $colors, $weight)
                ->set('focus:ring', $colors, $weight)
                ->merge('dark:ring-offset', 'dark', 900)
                ->get(),
        ];
    }
}
