<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\Facades\TallStackUi;
use TallStackUi\View\Components\Form\Toggle;

class ToggleColors
{
    public function __construct(protected Toggle $component)
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
